<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PracticalEvaluation extends Model
{
    protected $fillable = [
        'batch_subject_id',
        'reg_no',
        'assessor_mobile_no',
        'lab_work_marks',
        'micro_project',
        'open_ended_topic',
        'attendance_marks',
        'board_exam_marks'
    ];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }
}
