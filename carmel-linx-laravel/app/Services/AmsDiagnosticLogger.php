<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AmsDiagnosticLogger
{
    /**
     * Safely log an error or diagnostic trace into `ams_system_logs` without interrupting execution.
     */
    public static function log(string $message, string $severity = 'INFO', ?string $department = null, ?array $context = null, ?Throwable $exception = null): void
    {
        try {
            DB::table('ams_system_logs')->insert([
                'user_id' => auth()->id() ?? null,
                'department' => $department ?? 'ACADEMIC',
                'endpoint' => request()->fullUrl() ?? 'CLI/BACKGROUND',
                'severity' => strtoupper($severity),
                'error_code' => $exception ? (string) $exception->getCode() : null,
                'message' => $message,
                'stack_trace' => $exception ? json_encode(explode("\n", $exception->getTraceAsString())) : null,
                'context' => $context ? json_encode($context) : null,
                'status' => 'UNRESOLVED',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (Throwable $e) {
            // Fallback to standard Laravel log if DB logging fails, ensuring zero crashes
            Log::channel('single')->error('AMS Diagnostic Catcher Fallback: ' . $e->getMessage());
        }
    }
}
