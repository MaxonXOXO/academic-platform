<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class R21MajorProjectCourseFile extends Model
{
    protected $table = 'r21_major_project_course_files';

    protected $fillable = [
        'batch_subject_id',
        'syllabus_pdf_path',
        'course_title',
        'course_code',
        'semester',
        'cia_marks',
        'ese_marks',
        'credits',
        'parsed_cos',
        'parsed_copo',
        'project_groups',
        'attainment_settings'
    ];

    protected $casts = [
        'parsed_cos' => 'array',
        'parsed_copo' => 'array',
        'project_groups' => 'array',
        'attainment_settings' => 'array',
    ];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class, 'batch_subject_id');
    }
}
