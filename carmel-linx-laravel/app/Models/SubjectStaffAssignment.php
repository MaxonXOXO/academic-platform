<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectStaffAssignment extends Model
{
    protected $fillable = [
        'batch_subject_id',
        'staff_mobile_no'
    ];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class, 'batch_subject_id', 'id');
    }

    public function staffProfile()
    {
        return $this->belongsTo(StaffProfile::class, 'staff_mobile_no', 'mobile_no');
    }

    public function staff()
    {
        return $this->belongsTo(StaffProfile::class, 'staff_mobile_no', 'mobile_no');
    }
}
