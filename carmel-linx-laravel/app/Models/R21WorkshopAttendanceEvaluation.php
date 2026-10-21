<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R21WorkshopAttendanceEvaluation extends Model
{
    use HasFactory;

    protected $table = 'r21_workshop_attendance_evaluations';

    protected $fillable = [
        'batch_subject_id',
        'reg_no',
        'total_hours',
        'attended_hours',
        'attendance_percentage',
        'teams_percentage',
        'override_percentage',
        'final_percentage',
        'attendance_mark',
        'override_mark',
        'final_attendance_mark',
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
