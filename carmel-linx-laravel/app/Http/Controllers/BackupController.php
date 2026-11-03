<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    /**
     * Generate SQL Backup of the local database and upload it to Google Drive.
     */
    public function backupDatabaseToDrive()
    {
        try {
            $fileName = 'backup_' . date('Y_m_d_His') . '.sql';
            $tempDir = storage_path('app/backups');
            if (!file_exists($tempDir)) {
                @mkdir($tempDir, 0777, true);
                @chmod($tempDir, 0777);
            }
            if (!is_writable($tempDir)) {
                $tempDir = sys_get_temp_dir();
            }
            $filePath = $tempDir . '/' . $fileName;

            $this->generateSqlDumpFile($filePath);

            // Upload to Google Drive via API
            $driveResponse = $this->uploadFileToGoogleDrive($filePath, $fileName);

            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'Database SQL backup compiled and uploaded to Google Drive successfully!',
                'file_name' => $fileName,
                'drive_details' => $driveResponse
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Backup failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download instant local SQL backup directly to browser without memory exhaustion.
     */
    public function downloadLocalBackup()
    {
        $role = session('userRole');
        if (!in_array($role, ['Super_Admin', 'Principal', 'Admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $fileName = 'carmel_linx_backup_' . date('Y-m-d_His') . '.sql';
            $tempDir = storage_path('app/backups');
            if (!file_exists($tempDir)) {
                @mkdir($tempDir, 0777, true);
                @chmod($tempDir, 0777);
            }
            if (!is_writable($tempDir)) {
                $tempDir = sys_get_temp_dir();
            }
            $filePath = $tempDir . '/' . $fileName;

            $this->generateSqlDumpFile($filePath);

            return response()->download($filePath, $fileName, [
                'Content-Type' => 'application/x-sql',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Backup failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Generate SQL dump file to specified file path safely without memory exhaustion.
     */
    private function generateSqlDumpFile(&$filePath)
    {
        $dbHost = config('database.connections.mysql.host', '127.0.0.1');
        $dbPort = config('database.connections.mysql.port', '3306');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        // 1. Primary: Use mysqldump (fast, memory-free, handles large databases effortlessly)
        if (function_exists('exec')) {
            $cmd = sprintf(
                'mysqldump --host=%s --port=%s --user=%s --password=%s --default-character-set=utf8mb4 --single-transaction --routines --triggers --events --add-drop-table %s > %s 2>&1',
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbUser),
                escapeshellarg($dbPass),
                escapeshellarg($dbName),
                escapeshellarg($filePath)
            );
            exec($cmd, $output, $returnVar);
            if ($returnVar === 0 && file_exists($filePath) && filesize($filePath) > 0) {
                @chmod($filePath, 0666);
                return true;
            }

            // If mysqldump failed due to directory permission, retry in system temp dir
            $tempDumpPath = sys_get_temp_dir() . '/' . basename($filePath);
            $cmd2 = sprintf(
                'mysqldump --host=%s --port=%s --user=%s --password=%s --default-character-set=utf8mb4 --single-transaction --routines --triggers --events --add-drop-table %s > %s 2>&1',
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbUser),
                escapeshellarg($dbPass),
                escapeshellarg($dbName),
                escapeshellarg($tempDumpPath)
            );
            exec($cmd2, $output2, $returnVar2);
            if ($returnVar2 === 0 && file_exists($tempDumpPath) && filesize($tempDumpPath) > 0) {
                @chmod($tempDumpPath, 0666);
                $filePath = $tempDumpPath;
                return true;
            }
        }

        // 2. Fallback: PHP streaming chunk-by-chunk to disk without memory exhaustion
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $fileHandle = @fopen($filePath, 'w');
        if (!$fileHandle) {
            $fallbackPath = sys_get_temp_dir() . '/' . basename($filePath);
            $fileHandle = @fopen($fallbackPath, 'w');
            if ($fileHandle) {
                $filePath = $fallbackPath;
            } else {
                $lastErr = error_get_last()['message'] ?? 'Permission denied';
                throw new \Exception("Unable to open backup file for writing at {$filePath}: {$lastErr}");
            }
        }

        fwrite($fileHandle, "-- Carmel Linx Database Backup\n");
        fwrite($fileHandle, "-- Generated: " . date('Y-m-d H:i:s') . "\n");
        fwrite($fileHandle, "-- Database: " . $dbName . "\n\n");
        fwrite($fileHandle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

        $tables = DB::select('SHOW TABLES');
        $dbNameKey = 'Tables_in_' . $dbName;

        foreach ($tables as $table) {
            $tableName = $table->$dbNameKey;
            fwrite($fileHandle, "DROP TABLE IF EXISTS `{$tableName}`;\n");

            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $createTableKey = 'Create Table';
            fwrite($fileHandle, $createTable[0]->$createTableKey . ";\n\n");

            DB::table($tableName)->orderBy(DB::raw('1'))->chunk(500, function ($rows) use ($fileHandle, $tableName) {
                foreach ($rows as $row) {
                    $rowArray = (array)$row;
                    $columns = array_keys($rowArray);
                    $escapedValues = array_map(function ($value) {
                        if (is_null($value)) return 'NULL';
                        return "'" . addslashes($value) . "'";
                    }, array_values($rowArray));

                    fwrite($fileHandle, "INSERT INTO `{$tableName}` (`" . implode("`, `", $columns) . "`) VALUES (" . implode(", ", $escapedValues) . ");\n");
                }
            });

            fwrite($fileHandle, "\n\n");
        }

        fwrite($fileHandle, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($fileHandle);
        @chmod($filePath, 0666);

        return true;
    }

    /**
     * Restore database from uploaded SQL dump file.
     */
    public function restoreDatabase(Request $request)
    {
        $role = session('userRole');
        if (!in_array($role, ['Super_Admin', 'Principal', 'Admin'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized access.'], 403);
        }

        $request->validate([
            'sql_file' => 'required|file',
        ]);

        try {
            $file = $request->file('sql_file');
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, ['sql', 'txt'])) {
                return response()->json(['status' => 'ERROR', 'message' => 'Invalid file format. Please select a valid .sql backup file.'], 422);
            }

            $realPath = $file->getRealPath();
            if (!file_exists($realPath) || filesize($realPath) === 0) {
                return response()->json(['status' => 'ERROR', 'message' => 'The uploaded SQL file is empty.'], 422);
            }

            $dbHost = config('database.connections.mysql.host', '127.0.0.1');
            $dbPort = config('database.connections.mysql.port', '3306');
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');

            // 1. Primary: Use mysql CLI client (memory-safe, fast)
            if (function_exists('exec')) {
                $cmd = sprintf(
                    'mysql --host=%s --port=%s --user=%s --password=%s --default-character-set=utf8mb4 %s < %s 2>&1',
                    escapeshellarg($dbHost),
                    escapeshellarg($dbPort),
                    escapeshellarg($dbUser),
                    escapeshellarg($dbPass),
                    escapeshellarg($dbName),
                    escapeshellarg($realPath)
                );
                exec($cmd, $output, $returnVar);
                if ($returnVar === 0) {
                    return response()->json([
                        'status' => 'SUCCESS',
                        'message' => 'Database successfully restored from "' . $file->getClientOriginalName() . '"!'
                    ]);
                }
            }

            // 2. Fallback: PHP memory increase & unprepared statement
            ini_set('memory_limit', '512M');
            set_time_limit(300);

            $sqlContent = file_get_contents($realPath);
            if (empty(trim($sqlContent))) {
                return response()->json(['status' => 'ERROR', 'message' => 'The uploaded SQL file is empty.'], 422);
            }

            DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
            DB::unprepared($sqlContent);
            DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'Database successfully restored from "' . $file->getClientOriginalName() . '"!'
            ]);
        } catch (\Exception $e) {
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
            } catch (\Exception $ex) {}

            return response()->json([
                'status' => 'ERROR',
                'message' => 'Database restoration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper to authenticate and upload file to Google Drive via OAuth 2.0.
     */
    private function uploadFileToGoogleDrive($filePath, $fileName)
    {
        $clientId = env('GOOGLE_DRIVE_CLIENT_ID');
        $clientSecret = env('GOOGLE_DRIVE_CLIENT_SECRET');
        $refreshToken = env('GOOGLE_DRIVE_REFRESH_TOKEN');
        $parentFolderId = env('GOOGLE_DRIVE_FOLDER_ID');

        if (!$clientId || !$clientSecret || !$refreshToken) {
            throw new \Exception('Google Drive OAuth credentials are not fully configured in your .env file.');
        }

        // 1. Fetch Google Access Token using Refresh Token
        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token',
        ]);

        if ($tokenResponse->failed()) {
            throw new \Exception('Failed to retrieve access token from Google: ' . $tokenResponse->body());
        }

        $accessToken = $tokenResponse->json()['access_token'];

        // 2. Upload file contents to Google Drive API
        $metadata = [
            'name' => $fileName,
            'mimeType' => 'text/plain',
        ];
        
        if ($parentFolderId) {
            $metadata['parents'] = [$parentFolderId];
        }

        $fileData = file_get_contents($filePath);

        $uploadResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->attach(
            'metadata', 
            json_encode($metadata), 
            'metadata.json', 
            ['Content-Type' => 'application/json; charset=UTF-8']
        )->attach(
            'file', 
            $fileData, 
            $fileName, 
            ['Content-Type' => 'application/octet-stream']
        )->post('https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart');

        if ($uploadResponse->failed()) {
            throw new \Exception('Failed to upload file to Google Drive: ' . $uploadResponse->body());
        }

        return $uploadResponse->json();
    }
}
