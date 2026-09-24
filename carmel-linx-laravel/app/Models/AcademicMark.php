<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AcademicMark extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'academic_marks';

    protected $primaryKey = 'mark_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'mark_id',
        'reg_no',
        'subject_code',
        'batch_subject_id',
        'category',
        'co_tag',
        'max_marks',
        'marks_obtained',
        'entered_by',
    ];

    /**
     * Relationship: The student this grade belongs to.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }

    /**
     * Relationship: The staff who entered the marks.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(StaffProfile::class, 'entered_by', 'mobile_no');
    }
}
