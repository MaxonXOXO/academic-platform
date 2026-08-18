<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffBiometricCredential extends Model
{
    use HasFactory;

    protected $table = 'staff_biometric_credentials';

    protected $fillable = [
        'staff_mobile_no',
        'credential_id',
        'public_key',
        'device_name',
        'counter',
    ];

    /**
     * Relationship to StaffProfile
     */
    public function staffProfile()
    {
        return $this->belongsTo(StaffProfile::class, 'staff_mobile_no', 'mobile_no');
    }
}
