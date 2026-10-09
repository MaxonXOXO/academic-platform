<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectOfficialAttendance extends Model
{
    protected $table = 'subject_official_attendances';

    protected $fillable = [
        'batch_subject_id',
        'subject_code',
        'reg_no',
        'classroom_id',
        'total_hours',
        'attended_hours',
        'teams_percentage',
        'override_percentage',
        'final_percentage',
        'max_attendance_marks',
        'attendance_mark',
        'override_mark',
        'final_mark',
        'source',
        'remarks'
    ];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class, 'batch_subject_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }
}
