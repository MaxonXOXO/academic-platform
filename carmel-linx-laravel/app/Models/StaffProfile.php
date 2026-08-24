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
}
