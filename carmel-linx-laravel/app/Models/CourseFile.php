<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_subject_id',
        'syllabus_pdf_path',
        'parsed_modules',
        'parsed_cos',
        'parsed_copo',
        'parsed_textbooks',
        'assignment_deadlines',
        'assignment_questions',
        'summative_manual_tests',
        'self_learning_configs',
        'attainment_settings'
    ];

    protected $casts = [
        'parsed_modules' => 'array',
        'parsed_cos' => 'array',
        'parsed_copo' => 'array',
        'parsed_textbooks' => 'array',
        'assignment_deadlines' => 'array',
        'assignment_questions' => 'array',
        'summative_manual_tests' => 'array',
        'self_learning_configs' => 'array',
        'attainment_settings' => 'array'
    ];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class, 'batch_subject_id');
    }
}
