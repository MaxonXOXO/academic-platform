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

    protected $appends = [
        'offering_branch_full',
    ];

    public static function getBranchFullName(?string $branch): string
    {
        $map = [
            'AU' => 'Automobile Engineering',
            'CE' => 'Civil Engineering',
            'CT' => 'Computer Engineering',
            'EEE' => 'Electrical & Electronics Engineering',
            'EL' => 'Electronics Engineering',
            'ME' => 'Mechanical Engineering',
        ];
        $code = strtoupper(trim($branch ?? ''));
        return $map[$code] ?? ($branch ?: '');
    }

    public function getOfferingBranchFullAttribute(): string
    {
        return self::getBranchFullName($this->offering_branch);
    }

    public function getOfferingBranchFullNameAttribute(): string
    {
        return self::getBranchFullName($this->offering_branch);
    }

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
