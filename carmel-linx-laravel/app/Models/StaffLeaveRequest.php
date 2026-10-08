<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffLeaveRequest extends Model
{
    use HasFactory;

    protected $table = 'staff_leave_requests';

    protected $fillable = [
        'leave_code',
        'staff_mobile',
        'staff_name',
        'designation',
        'department',
        'leave_type',
        'from_date',
        'to_date',
        'session_type',
        'ccl_date',
        'total_days',
        'reason',
        'work_arrangement',
        'staff_signature_hash',
        'submitted_at',
        'office_status',
        'office_mobile',
        'office_name',
        'office_remarks',
        'office_action_at',
        'is_historical',
        'hod_status',
        'hod_mobile',
        'hod_name',
        'hod_remarks',
        'hod_action_at',
        'coordinator_status',
        'coordinator_mobile',
        'coordinator_name',
        'coordinator_remarks',
        'coordinator_action_at',
        'principal_status',
        'principal_mobile',
        'principal_name',
        'principal_remarks',
        'principal_action_at',
        'overall_status',
    ];

    protected $casts = [
        'from_date'              => 'date',
        'to_date'                => 'date',
        'ccl_date'               => 'date',
        'total_days'             => 'float',
        'is_historical'          => 'boolean',
        'work_arrangement'       => 'array',
        'submitted_at'           => 'datetime',
        'office_action_at'       => 'datetime',
        'hod_action_at'          => 'datetime',
        'coordinator_action_at'  => 'datetime',
        'principal_action_at'    => 'datetime',
    ];
}
