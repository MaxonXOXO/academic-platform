<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OeSetting extends Model
{
    protected $table = 'oe_settings';

    protected $fillable = [
        'academic_year',
        'semester',
        'reg_start_at',
        'reg_end_at',
        'is_registration_open',
        'is_released_to_hods',
        'released_at',
        'released_by_staff_id',
        'push_title',
        'push_body',
    ];

    protected $casts = [
        'semester' => 'integer',
        'reg_start_at' => 'datetime',
        'reg_end_at' => 'datetime',
        'released_at' => 'datetime',
        'is_registration_open' => 'boolean',
        'is_released_to_hods' => 'boolean',
    ];

    public static function getActiveSettings()
    {
        return self::firstOrCreate(
            ['academic_year' => '2026-2027', 'semester' => 6],
            [
                'push_title' => 'S6 Open Elective Registration Open',
                'push_body' => 'Please select your 6th semester Open Elective preferences on Carmel Linx.',
                'is_registration_open' => false,
                'is_released_to_hods' => false,
            ]
        );
    }
}
