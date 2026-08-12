<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchSubject extends Model
{
    protected $fillable = [
        'classroom_id',
        'semester',
        'subject_code',
        'subject_name',
        'subject_type',
        'syllabus_revision_code'
    ];

    public function classroom()
    {
        return $this->belongsTo(ClassManagement::class, 'classroom_id', 'classroom_id');
    }

    public function staffAssignments()
    {
        return $this->hasMany(SubjectStaffAssignment::class, 'batch_subject_id', 'id');
    }

    public function courseFile()
    {
        return $this->hasOne(CourseFile::class, 'batch_subject_id', 'id');
    }

    /**
     * Universal Branch-Prefixed Subject Code Accessor
     * e.g. "1003" -> "ME-1003" or "CE-1003"
     */
    public function getFormattedSubjectCodeAttribute()
    {
        $code = trim($this->subject_code ?? '');
        if (empty($code)) return '';

        // If code already starts with a branch prefix and hyphen (e.g. ME-1003, CE-1003, EEE-1003, CT-1003, AE-1003, ECE-1003)
        if (preg_match('/^[A-Za-z]{2,5}-\d+$/i', $code)) {
            return strtoupper($code);
        }

        $prefix = '';

        // 1. Try classroom branch or department
        $branch = $this->classroom->branch ?? $this->classroom->department ?? null;
        if (!empty($branch)) {
            $b = strtoupper(trim($branch));
            if (str_contains($b, 'MECH') || $b === 'ME') $prefix = 'ME';
            elseif (str_contains($b, 'CIVIL') || $b === 'CE') $prefix = 'CE';
            elseif (str_contains($b, 'ELECTRICAL') || $b === 'EEE') $prefix = 'EEE';
            elseif (str_contains($b, 'ELECTRON') || $b === 'ECE') $prefix = 'ECE';
            elseif (str_contains($b, 'COMPUTER') || $b === 'CT') $prefix = 'CT';
            elseif (str_contains($b, 'AUTO') || $b === 'AE') $prefix = 'AE';
            else {
                $letters = preg_replace('/[^A-Z]/', '', $b);
                if (!empty($letters)) {
                    $prefix = strlen($letters) > 4 ? substr($letters, 0, 3) : $letters;
                }
            }
        }

        // 2. Try classroom_id prefix if branch string was empty (e.g. "CE-2026-A" -> "CE")
        if (empty($prefix) && !empty($this->classroom_id)) {
            $parts = explode('-', $this->classroom_id);
            if (!empty($parts[0]) && preg_match('/^[A-Za-z]{2,5}$/', $parts[0])) {
                $prefix = strtoupper(trim($parts[0]));
            }
        }

        if (empty($prefix)) {
            $prefix = 'ME';
        }

        // Clean any un-hyphenated prefix if already present (e.g. ME1003 -> 1003)
        $cleanCode = preg_replace('/^' . preg_quote($prefix, '/') . '[-_]?/i', '', $code);

        return strtoupper($prefix . '-' . $cleanCode);
    }
}
