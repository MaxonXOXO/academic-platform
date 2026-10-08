<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SfStaffCclCredit extends Model
{
    use HasFactory;

    protected $table = 'sf_staff_ccl_credits';

    protected $fillable = [
        'staff_mobile',
        'staff_name',
        'duty_date',
        'session_type',
        'earned_days',
        'used_days',
        'valid_until',
        'source_punch_id',
        'status',
        'remarks',
        'credited_by',
    ];

    protected $casts = [
        'duty_date'   => 'date',
        'valid_until' => 'date',
        'earned_days' => 'float',
        'used_days'   => 'float',
    ];

    public function staffProfile()
    {
        return $this->belongsTo(StaffProfile::class, 'staff_mobile', 'mobile_no');
    }

    public function sourcePunch()
    {
        return $this->belongsTo(SfStaffTimePunch::class, 'source_punch_id');
    }
}
