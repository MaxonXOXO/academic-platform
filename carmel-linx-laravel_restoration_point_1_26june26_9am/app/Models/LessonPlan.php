<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_subject_id',
        'day_no',
        'co_id',
        'topic_content',
        'allocated_hours',
        'proposed_date',
        'actual_date',
        'actual_hours',
        'pedagogy',
        'remarks',
        'status',
    ];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class, 'batch_subject_id');
    }
}
