<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R21WorkshopCiaSummary extends Model
{
    use HasFactory;

    protected $table = 'r21_workshop_cia_summaries';

    protected $fillable = [
        'batch_subject_id',
        'reg_no',
        'work_score_375',
        'open_ended_75',
        'test_avg_15',
        'attendance_15',
        'total_cia_75',
        'override_work_375',
        'override_open_ended_75',
        'override_test_15',
        'override_attendance_15',
        'override_total_cia',
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
