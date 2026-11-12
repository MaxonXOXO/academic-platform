<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OeRegistration extends Model
{
    protected $table = 'oe_registrations';

    protected $fillable = [
        'student_adm_no',
        'sbte_reg_no',
        'student_name',
        'parent_branch',
        'option_1_course_id',
        'option_2_course_id',
        'option_3_course_id',
        'option_4_course_id',
        'option_5_course_id',
        'allotted_course_id',
        'allocation_source',
        'registered_at',
        'ip_address',
        'status',
    ];

    protected $casts = [
        'option_1_course_id' => 'integer',
        'option_2_course_id' => 'integer',
        'option_3_course_id' => 'integer',
        'option_4_course_id' => 'integer',
        'option_5_course_id' => 'integer',
        'allotted_course_id' => 'integer',
        'registered_at' => 'datetime',
    ];

    protected $appends = [
        'parent_branch_full',
        'is_complete_five_options',
    ];

    public function getParentBranchFullAttribute(): string
    {
        return OeCourse::getBranchFullName($this->parent_branch);
    }

    public function getParentBranchFullNameAttribute(): string
    {
        return OeCourse::getBranchFullName($this->parent_branch);
    }

    public function getIsCompleteFiveOptionsAttribute(): bool
    {
        return $this->hasAllFiveOptions();
    }

    public function hasAllFiveOptions(): bool
    {
        return !empty($this->option_1_course_id)
            && !empty($this->option_2_course_id)
            && !empty($this->option_3_course_id)
            && !empty($this->option_4_course_id)
            && !empty($this->option_5_course_id);
    }

    public function option1Course()
    {
        return $this->belongsTo(OeCourse::class, 'option_1_course_id');
    }

    public function option2Course()
    {
        return $this->belongsTo(OeCourse::class, 'option_2_course_id');
    }

    public function option3Course()
    {
        return $this->belongsTo(OeCourse::class, 'option_3_course_id');
    }

    public function option4Course()
    {
        return $this->belongsTo(OeCourse::class, 'option_4_course_id');
    }

    public function option5Course()
    {
        return $this->belongsTo(OeCourse::class, 'option_5_course_id');
    }

    public function allottedCourse()
    {
        return $this->belongsTo(OeCourse::class, 'allotted_course_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_adm_no', 'adm_no');
    }
}
