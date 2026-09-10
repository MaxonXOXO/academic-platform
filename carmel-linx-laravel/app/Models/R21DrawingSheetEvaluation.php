<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R21DrawingSheetEvaluation extends Model
{
    use HasFactory;

    protected $table = 'r21_drawing_sheet_evaluations';

    protected $fillable = [
        'batch_subject_id',
        'sheet_no',
        'sheet_title',
        'module_no',
        'co_id',
        'reg_no',
        'timely_completion',
        'appearance_organization',
        'total_score_100',
        'is_absent',
        'remarks',
    ];

    protected $casts = [
        'timely_completion' => 'decimal:2',
        'appearance_organization' => 'decimal:2',
        'total_score_100' => 'decimal:2',
        'is_absent' => 'boolean',
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
