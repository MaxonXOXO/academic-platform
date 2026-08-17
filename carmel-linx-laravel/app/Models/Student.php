<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $primaryKey = 'reg_no';

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($student) {
            if (empty($student->classroom_id) && !empty($student->branch) && !empty($student->admission_year)) {
                $isLet = ($student->admission_type === 'LET');
                $startYear = $isLet ? ($student->admission_year - 1) : (int)$student->admission_year;
                $endYear = $startYear + 3;
                $student->classroom_id = "{$student->branch}_{$startYear}_{$endYear}";
            }
            if (empty($student->academic_status)) {
                $student->academic_status = 'Active';
            }
        });
    }

    protected $fillable = [
        'reg_no',
        'adm_no',
        'name',
        'email',
        'password',
        'phone',
        'branch',
        'admission_year',
        'admission_type',
        'photo_url',
        'classroom_id',
        'semester',
        'status',
        'sbte_reg_no',
        'mentor_mobile_no',
        'annual_income',
        'residential_status',
        'guardian_name',
        'guardian_address',
        'guardian_relationship',
        'guardian_mobile',
        'scholarships',
        'is_fee_waiver',
        'profile_verified_at',
        'profile_verified_by',
        'academic_status',
        'status_notes',
        'date_of_birth',
        'application_no',
        'quota',
        'date_of_joining',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Relationship: The classroom this student belongs to.
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(ClassManagement::class, 'classroom_id', 'classroom_id');
    }

    /**
     * Relationship: The assigned staff mentor.
     */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(StaffProfile::class, 'mentor_mobile_no', 'mobile_no');
    }

    /**
     * Relationship: Student's online test responses.
     */
    public function responses(): HasMany
    {
        return $this->hasMany(StudentResponse::class, 'reg_no', 'reg_no');
    }

    /**
     * Relationship: Student's academic marks list.
     */
    public function academicMarks(): HasMany
    {
        return $this->hasMany(AcademicMark::class, 'reg_no', 'reg_no');
    }

    public static function getClassroomStudentsQuery($classroomId)
    {
        if (str_ends_with($classroomId, '_LET')) {
            $baseClassroomId = substr($classroomId, 0, -4);
            return self::where('classroom_id', $baseClassroomId)
                ->where(function($q) {
                    $q->where('reg_no', 'like', '%L')
                      ->orWhere('sbte_reg_no', 'like', '%L');
                });
        }

        $classroom = ClassManagement::where('classroom_id', $classroomId)->first()
            ?? R26ClassManagement::where('classroom_id', $classroomId)->first();

        $branch = $classroom->branch ?? null;
        $batchYear = $classroom->batch_year ?? null;

        return self::where(function($query) use ($classroomId, $branch, $batchYear) {
            $query->where('classroom_id', $classroomId)
                  ->orWhere('classroom_id', str_replace('_', '-', $classroomId))
                  ->orWhere('classroom_id', str_replace('-', '_', $classroomId));
            
            if ($branch && $batchYear) {
                $query->orWhere(function($sub) use ($branch, $batchYear) {
                    $sub->where('branch', $branch)
                        ->where('admission_year', $batchYear);
                });
            }
        });
    }
}
