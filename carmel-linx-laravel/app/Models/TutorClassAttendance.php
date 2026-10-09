<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorClassAttendance extends Model
{
    protected $table = 'tutor_class_attendances';

    protected $fillable = [
        'classroom_id',
        'reg_no',
        'total_hours',
        'attended_hours',
        'attendance_percentage',
        'override_percentage',
        'final_percentage',
        'eligibility_status',
        'recorded_by',
        'source',
        'remarks'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }
}
