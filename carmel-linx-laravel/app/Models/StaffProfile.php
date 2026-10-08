<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StaffProfile extends Model
{
    use HasFactory;

    protected $table = 'staff_profiles';

    protected $fillable = [
        'mobile_no',
        'name',
        'email',
        'branch',
        'designation',
        'dob',
        'password',
        'remember_token',
        'photo_url',
        'account_status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $appends = [
        'is_online',
    ];

    public function getIsOnlineAttribute(): bool
    {
        return !empty($this->mobile_no) && \Illuminate\Support\Facades\Cache::has('user_online_' . $this->mobile_no);
    }

    /**
     * Relationship: Classes where this staff member is the primary tutor.
     */
    public function tutoredClasses(): HasMany
    {
        return $this->hasMany(ClassManagement::class, 'tutor_mobile_no', 'mobile_no');
    }

    /**
     * Relationship: Classes where this staff member is the primary mentor.
     */
    public function mentoredClasses(): HasMany
    {
        return $this->hasMany(ClassManagement::class, 'mentor_mobile_no', 'mobile_no');
    }

    /**
     * Relationship: Students assigned to this staff member as mentor.
     */
    public function mentoredStudents(): HasMany
    {
        return $this->hasMany(Student::class, 'mentor_mobile_no', 'mobile_no');
    }

    /**
     * Check if a staff mobile number is the designated Self-Financing Academic Coordinator.
     */
    public static function isSfAcademicCoordinator($mobileNo): bool
    {
        if (empty($mobileNo)) return false;
        $cleanMobile = preg_replace('/[^0-9]/', '', (string)$mobileNo);
        $coordMobile = \Illuminate\Support\Facades\DB::table('system_settings')
            ->where('key', 'sf_academic_coordinator_mobile')
            ->value('value');
        if (!empty($coordMobile)) {
            $cleanCoord = preg_replace('/[^0-9]/', '', (string)$coordMobile);
            if ($cleanMobile === $cleanCoord) return true;
        }
        // Configured default: Jacob Kurian (9495314331 - EEE HOD)
        return $cleanMobile === '9495314331';
    }
}
