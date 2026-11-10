<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OeCourse extends Model
{
    protected $table = 'oe_courses';

    protected $fillable = [
        'academic_year',
        'semester',
        'offering_branch',
        'course_code',
        'course_title',
        'course_description',
        'course_outcomes',
        'syllabus_pdf_path',
        'classroom_id',
        'batch_subject_id',
        'max_capacity',
        'faculty_name',
        'faculty_phone',
        'faculty_staff_id',
        'is_active',
        'is_hod_submitted',
    ];

    protected $casts = [
        'semester' => 'integer',
        'max_capacity' => 'integer',
        'is_active' => 'boolean',
        'is_hod_submitted' => 'boolean',
        'batch_subject_id' => 'integer',
    ];

    public function registrations()
    {
        return $this->hasMany(OeRegistration::class, 'allotted_course_id');
    }

    public function option1Registrations()
    {
        return $this->hasMany(OeRegistration::class, 'option_1_course_id');
    }

    public function virtualClassroomStudents()
    {
        return $this->hasMany(OeVirtualClassroom::class, 'oe_course_id');
    }

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class, 'batch_subject_id');
    }
}
