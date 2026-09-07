<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PracticalExperimentMark extends Model
{
    protected $fillable = [
        'practical_experiment_id',
        'reg_no',
        'assessor_mobile_no',
        'prerequisites',
        'work_done',
        'result',
        'rough_record',
        'fair_record',
        'total_mark',
        'evaluation_date'
    ];

    public function experiment()
    {
        return $this->belongsTo(PracticalExperiment::class, 'practical_experiment_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }
}
