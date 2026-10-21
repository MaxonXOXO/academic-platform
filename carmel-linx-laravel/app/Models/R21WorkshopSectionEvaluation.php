<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R21WorkshopSectionEvaluation extends Model
{
    use HasFactory;

    protected $table = 'r21_workshop_section_evaluations';

    protected $fillable = [
        'batch_subject_id',
        'reg_no',
        'section_name',
        'rough_record_mark',
        'fair_record_mark',
        'record_total_20',
        'alum_rough_record_mark',
        'alum_fair_record_mark',
        'alum_record_total_20',
        'test_mark',
        'is_test_absent',
        'remarks',
    ];

    protected $casts = [
        'is_test_absent' => 'boolean',
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
