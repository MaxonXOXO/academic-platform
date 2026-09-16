<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class R21MajorProjectEvaluation extends Model
{
    protected $table = 'r21_major_project_evaluations';

    protected $fillable = [
        'batch_subject_id',
        'reg_no',
        'group_id',
        'project_title',
        'formative_diary_marks',
        'summative_dept_marks',
        'attendance_marks',
        'total_cia_75',
        'ese_prototype',
        'ese_modern_tools',
        'ese_presentation',
        'ese_innovativeness',
        'ese_viva',
        'ese_individual_contrib',
        'ese_group_activity',
        'ese_project_report',
        'total_ese_50',
        'ese_grade',
        'grand_total_125',
        'passed',
        'remarks'
    ];

    protected $casts = [
        'formative_diary_marks' => 'float',
        'summative_dept_marks' => 'float',
        'attendance_marks' => 'float',
        'total_cia_75' => 'float',
        'ese_prototype' => 'float',
        'ese_modern_tools' => 'float',
        'ese_presentation' => 'float',
        'ese_innovativeness' => 'float',
        'ese_viva' => 'float',
        'ese_individual_contrib' => 'float',
        'ese_group_activity' => 'float',
        'ese_project_report' => 'float',
        'total_ese_50' => 'float',
        'grand_total_125' => 'float',
        'passed' => 'boolean'
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
