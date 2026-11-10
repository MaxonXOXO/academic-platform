<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OeVirtualClassroom extends Model
{
    protected $table = 'oe_virtual_classrooms';

    protected $fillable = [
        'oe_course_id',
        'classroom_id',
        'batch_subject_id',
        'student_adm_no',
        'sbte_reg_no',
        'student_name',
        'parent_branch',
        'roll_no',
        'academic_year',
        'semester',
        'assigned_faculty_id',
    ];

    protected $casts = [
        'oe_course_id' => 'integer',
        'semester' => 'integer',
        'batch_subject_id' => 'integer',
    ];

    public function course()
    {
        return $this->belongsTo(OeCourse::class, 'oe_course_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_adm_no', 'adm_no');
    }
}
