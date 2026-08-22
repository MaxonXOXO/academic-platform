<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrincipalScheduledEvent extends Model
{
    use HasFactory;

    protected $table = 'principal_scheduled_events';

    protected $fillable = [
        'title',
        'description',
        'event_category',
        'venue',
        'event_date',
        'start_time',
        'end_time',
        'is_full_day',
        'suppress_timetable',
        'suspension_type',
        'end_date',
        'reopen_date',
        'target_audience',
        'target_department',
        'target_semester',
        'target_role',
        'special_group_name',
        'requires_rsvp',
        'attachment_path',
        'attachment_type',
        'dispatch_type',
        'scheduled_at',
        'is_published',
        'created_by',
    ];

    protected $casts = [
        'is_full_day'        => 'boolean',
        'suppress_timetable' => 'boolean',
        'requires_rsvp'      => 'boolean',
        'is_published'       => 'boolean',
        'event_date'         => 'date',
        'end_date'           => 'date',
        'reopen_date'        => 'date',
        'scheduled_at'       => 'datetime',
    ];
}
