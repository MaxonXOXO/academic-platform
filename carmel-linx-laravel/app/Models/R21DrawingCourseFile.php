<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R21DrawingCourseFile extends Model
{
    use HasFactory;

    protected $table = 'r21_drawing_course_files';

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
        'parsed_cos',
        'parsed_modules',
        'parsed_sheets',
        'parsed_copo',
        'parsed_textbooks',
        'series_test_qps',
    ];

    protected $casts = [
        'parsed_cos' => 'array',
        'parsed_modules' => 'array',
        'parsed_sheets' => 'array',
        'parsed_copo' => 'array',
        'parsed_textbooks' => 'array',
        'series_test_qps' => 'array',
    ];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class, 'batch_subject_id');
    }
}
