<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramAttainment extends Model
{
    use HasFactory;

    protected $table = 'program_attainments';

    protected $fillable = [
        'classroom_id',
        'branch',
        'batch_year',
        'revision',
        'po_targets',
        'indirect_surveys',
        'action_plans',
        'cached_results',
        'status'
    ];

    protected $casts = [
        'po_targets' => 'array',
        'indirect_surveys' => 'array',
        'action_plans' => 'array',
        'cached_results' => 'array'
    ];
}
