<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R21DrawingAttendanceEvaluation extends Model
{
    use HasFactory;

    protected $table = 'r21_drawing_attendance_evaluations';

    protected $fillable = [
        'batch_subject_id',
        'reg_no',
        'total_hours',
        'attended_hours',
        'attendance_percentage',
        'attendance_mark',
        'override_mark',
        'final_attendance_mark',
    ];

    protected $casts = [
        'attendance_percentage' => 'decimal:2',
        'attendance_mark' => 'decimal:2',
        'override_mark' => 'decimal:2',
        'final_attendance_mark' => 'decimal:2',
    ];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class, 'batch_subject_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }
}
