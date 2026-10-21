<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R21WorkshopStudentAllocation extends Model
{
    use HasFactory;

    protected $table = 'r21_workshop_student_allocations';

    protected $fillable = [
        'batch_subject_id',
        'reg_no',
        'batch_no',
        'assigned_sections',
    ];

    protected $casts = [
        'assigned_sections' => 'array',
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
