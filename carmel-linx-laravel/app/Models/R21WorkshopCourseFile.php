<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R21WorkshopCourseFile extends Model
{
    use HasFactory;

    protected $table = 'r21_workshop_course_files';

    protected $fillable = [
        'batch_subject_id',
        'syllabus_pdf_path',
        'program',
        'course_title',
        'course_code',
        'semester',
        'type_of_course',
        'teaching_scheme',
        'contact_hours',
        'credits',
        'cia_marks',
        'ese_marks',
        'sections_config',
        'batch_allocation',
        'batch_split_cutoff',
        'parsed_cos',
        'parsed_copo',
    ];

    protected $casts = [
        'sections_config' => 'array',
        'batch_allocation' => 'array',
        'parsed_cos' => 'array',
        'parsed_copo' => 'array',
    ];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class, 'batch_subject_id');
    }
}
