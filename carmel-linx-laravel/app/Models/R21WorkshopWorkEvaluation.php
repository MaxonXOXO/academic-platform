<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R21WorkshopWorkEvaluation extends Model
{
    use HasFactory;

    protected $table = 'r21_workshop_work_evaluations';

    protected $fillable = [
        'batch_subject_id',
        'reg_no',
        'section_name',
        'work_1_mark',
        'work_2_mark',
        'work_3_mark',
        'work_4_mark',
        'alum_work_mark',
        'work_avg_20',
        'remarks',
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
