<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R26EnglishLessonPlan extends Model
{
    use HasFactory;

    protected $table = 'r26_english_lesson_plans';

    protected $fillable = [
        'batch_subject_id',
        'day_no',
        'proposed_date',
        'actual_date',
        'topic_content',
        'module_id',
        'co_tag',
        'allocated_hours',
        'mode',
        'pedagogy',
        'status',
        'remarks',
    ];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class, 'batch_subject_id');
    }
}
