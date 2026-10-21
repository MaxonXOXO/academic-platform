<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R26TheoryEvaluation extends Model
{
    use HasFactory;

    protected $table = 'r26_theory_evaluations';

    protected $fillable = [
        'batch_subject_id',
        'reg_no',
        'attendance_pct',
        'attendance_override_pct',
        'attendance_mark_5',
        'ca1_15',
        'ca2_15',
        'ca3_15',
        'ca4_15',
        'self_learning_15',
        'series_1_50',
        'series_1_co1_25',
        'series_1_co2_25',
        'series_1_absent',
        'series_2_50',
        'series_2_co3_25',
        'series_2_co4_25',
        'series_2_absent',
        'series_20',
        'total_cia_40',
        'ese_grade',
        'ese_mark_60',
        'ese_absent',
        'total_marks_100',
    ];

    protected $casts = [
        'attendance_pct' => 'float',
        'attendance_override_pct' => 'float',
        'attendance_mark_5' => 'float',
        'ca1_15' => 'float',
        'ca2_15' => 'float',
        'ca3_15' => 'float',
        'ca4_15' => 'float',
        'self_learning_15' => 'float',
        'series_1_50' => 'float',
        'series_1_co1_25' => 'float',
        'series_1_co2_25' => 'float',
        'series_1_absent' => 'boolean',
        'series_2_50' => 'float',
        'series_2_co3_25' => 'float',
        'series_2_co4_25' => 'float',
        'series_2_absent' => 'boolean',
        'series_20' => 'float',
        'total_cia_40' => 'float',
        'ese_mark_60' => 'float',
        'ese_absent' => 'boolean',
        'total_marks_100' => 'float',
    ];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class, 'batch_subject_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
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
}
