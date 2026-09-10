<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R21DrawingSeriesTest extends Model
{
    use HasFactory;

    protected $table = 'r21_drawing_series_tests';

    protected $fillable = [
        'batch_subject_id',
        'test_no',
        'reg_no',
        'procedure_drawing',
        'final_drawing',
        'dimensioning',
        'neatness',
        'total_score_100',
        'is_absent',
        'remarks',
    ];

    protected $casts = [
        'procedure_drawing' => 'decimal:2',
        'final_drawing' => 'decimal:2',
        'dimensioning' => 'decimal:2',
        'neatness' => 'decimal:2',
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
