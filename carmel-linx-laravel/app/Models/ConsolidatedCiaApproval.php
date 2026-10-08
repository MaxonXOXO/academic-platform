<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsolidatedCiaApproval extends Model
{
    protected $table = 'consolidated_cia_approvals';

    protected $fillable = [
        'classroom_id',
        'semester',
        'academic_year',
        'is_locked',
        'approved_by_hod',
        'hod_user_id',
        'hod_name',
        'approved_at',
        'submitted_by_tutor',
        'tutor_user_id',
        'tutor_name',
        'submitted_at',
        'remarks',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
        'approved_by_hod' => 'boolean',
        'submitted_by_tutor' => 'boolean',
        'approved_at' => 'datetime',
        'submitted_at' => 'datetime',
        'semester' => 'integer',
    ];

    /**
     * Check whether CIA is currently locked for a classroom & semester.
     */
    public static function isLocked(string $classroomId, int $semester): bool
    {
        $rec = static::where('classroom_id', $classroomId)
            ->where('semester', $semester)
            ->first();

        return (bool)($rec && $rec->is_locked);
    }

    /**
     * Check whether CIA is currently locked for a given batch subject.
     */
    public static function isLockedForSubject($batchSubjectId): bool
    {
        $subj = BatchSubject::find($batchSubjectId);
        if (!$subj) return false;
        return static::isLocked($subj->classroom_id, (int)$subj->semester);
    }
}
