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
            $tables = DB::select('SHOW TABLES');
            $dbNameKey = 'Tables_in_' . config('database.connections.mysql.database');
            
            $sqlContent = "-- Carmel Linx Database Backup\n";
            $sqlContent .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";

            foreach ($tables as $table) {
                $tableName = $table->$dbNameKey;
                
                // Drop table if exists for clean restoration
                $sqlContent .= "DROP TABLE IF EXISTS `{$tableName}`;\n";

                // Fetch structure
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $createTableKey = 'Create Table';
                $sqlContent .= $createTable[0]->$createTableKey . ";\n\n";

                // Fetch rows
                $rows = DB::table($tableName)->get();
                foreach ($rows as $row) {
                    $rowArray = (array)$row;
                    $columns = array_keys($rowArray);
                    $escapedValues = array_map(function ($value) {
                        if (is_null($value)) return 'NULL';
                        return "'" . addslashes($value) . "'";
                    }, array_values($rowArray));

                    $sqlContent .= "INSERT INTO `{$tableName}` (`" . implode("`, `", $columns) . "`) VALUES (" . implode(", ", $escapedValues) . ");\n";
                }
                $sqlContent .= "\n\n";
            }

            // Save backup locally in temp folder
            $fileName = 'backup_' . date('Y_m_d_His') . '.sql';
            Storage::disk('local')->put('backups/' . $fileName, $sqlContent);
            $filePath = storage_path('app/backups/' . $fileName);

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
     * Download instant local SQL backup directly to browser.
     */
    public function downloadLocalBackup()
    {
        $role = session('userRole');
        if (!in_array($role, ['Super_Admin', 'Principal', 'Admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $tables = DB::select('SHOW TABLES');
            $dbNameKey = 'Tables_in_' . config('database.connections.mysql.database');
            
            $sqlContent = "-- Carmel Linx Database Backup\n";
            $sqlContent .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
            $sqlContent .= "-- Database: " . config('database.connections.mysql.database') . "\n\n";

            foreach ($tables as $table) {
                $tableName = $table->$dbNameKey;
                
                $sqlContent .= "DROP TABLE IF EXISTS `{$tableName}`;\n";

                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $createTableKey = 'Create Table';
                $sqlContent .= $createTable[0]->$createTableKey . ";\n\n";

                $rows = DB::table($tableName)->get();
                foreach ($rows as $row) {
                    $rowArray = (array)$row;
                    $columns = array_keys($rowArray);
                    $escapedValues = array_map(function ($value) {
                        if (is_null($value)) return 'NULL';
                        return "'" . addslashes($value) . "'";
                    }, array_values($rowArray));

                    $sqlContent .= "INSERT INTO `{$tableName}` (`" . implode("`, `", $columns) . "`) VALUES (" . implode(", ", $escapedValues) . ");\n";
                }
                $sqlContent .= "\n\n";
            }

            $fileName = 'carmel_linx_backup_' . date('Y-m-d_His') . '.sql';

            return response($sqlContent, 200, [
                'Content-Type' => 'application/x-sql',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Backup failed: ' . $e->getMessage()], 500);
        }
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

            $sqlContent = file_get_contents($file->getRealPath());
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
