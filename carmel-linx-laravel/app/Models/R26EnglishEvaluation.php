<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R26EnglishEvaluation extends Model
{
    use HasFactory;

    protected $table = 'r26_english_evaluations';

    protected $fillable = [
        'batch_subject_id',
        'reg_no',
        'attendance_pct',
        'attendance_override_pct',
        'attendance_mark_5',
        'sl_m1_10',
        'sl_m2_10',
        'sl_m3_10',
        'sl_m4_10',
        'sl_avg_10',
        'sl_conv_5',
        'ce_m1_50',
        'ce_m2_50',
        'ce_m3_50',
        'ce_m4_50',
        'ce_avg_50',
        'ce_conv_10',
        'prac_test1_40',
        'prac_test1_absent',
        'prac_test1_10',
        'prac_test2_40',
        'prac_test2_absent',
        'prac_test2_10',
        'prac_avg_40',
        'prac_conv_10',
        'series1_50',
        'series1_absent',
        'series1_10',
        'series2_50',
        'series2_absent',
        'series2_10',
        'series_avg_50',
        'series_conv_10',
        'total_cia_40',
        'ese_mark_60',
        'ese_grade',
        'ese_absent',
        'total_marks_100',
        'is_passed',
        'updated_by',
    ];

    protected $casts = [
        'attendance_pct' => 'float',
        'attendance_override_pct' => 'float',
        'attendance_mark_5' => 'float',
        'sl_m1_10' => 'float',
        'sl_m2_10' => 'float',
        'sl_m3_10' => 'float',
        'sl_m4_10' => 'float',
        'sl_avg_10' => 'float',
        'sl_conv_5' => 'float',
        'ce_m1_50' => 'float',
        'ce_m2_50' => 'float',
        'ce_m3_50' => 'float',
        'ce_m4_50' => 'float',
        'ce_avg_50' => 'float',
        'ce_conv_10' => 'float',
        'prac_test1_40' => 'float',
        'prac_test1_absent' => 'boolean',
        'prac_test1_10' => 'float',
        'prac_test2_40' => 'float',
        'prac_test2_absent' => 'boolean',
        'prac_test2_10' => 'float',
        'prac_avg_40' => 'float',
        'prac_conv_10' => 'float',
        'series1_50' => 'float',
        'series1_absent' => 'boolean',
        'series1_10' => 'float',
        'series2_50' => 'float',
        'series2_absent' => 'boolean',
        'series2_10' => 'float',
        'series_avg_50' => 'float',
        'series_conv_10' => 'float',
        'total_cia_40' => 'float',
        'ese_mark_60' => 'float',
        'ese_absent' => 'boolean',
        'total_marks_100' => 'float',
        'is_passed' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class, 'batch_subject_id');
    }

    /**
     * Calculate Attendance Marks (Max 5) from percentage based on R2026 rubric:
     * >= 90%  : 5 marks
     * 80% - 89.99% : 4 marks
     * 75% - 79.99% : 3 marks
     * 70% - 74.99% : 2 marks
     * 65% - 69.99% : 1 mark
     * < 65%   : 0 marks
     */
    public static function calculateAttendanceMarks($percentage)
    {
        if ($percentage === null || $percentage === '') return 0.0;
        $pct = (float)$percentage;
        if ($pct >= 90.0) return 5.0;
        if ($pct >= 80.0) return 4.0;
        if ($pct >= 75.0) return 3.0;
        if ($pct >= 70.0) return 2.0;
        if ($pct >= 65.0) return 1.0;
        return 0.0;
    }

    /**
     * Convert Board Grade to equivalent midpoint marks out of 60 if numeric mark is not explicitly typed
     */
    public static function gradeToMarks60(?string $grade): ?float
    {
        if (!$grade) return null;
        return match (strtoupper(trim($grade))) {
            'S' => 57.0,
            'A' => 51.0,
            'B' => 45.0,
            'C' => 39.0,
            'D' => 33.0,
            'E' => 27.0,
            'P' => 24.0,
            'F', 'FE', 'I', 'AB', 'ABSENT' => 0.0,
            default => null,
        };
    }
}
