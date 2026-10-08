<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorSpecialAttendance extends Model
{
    use HasFactory;

    protected $table = 'tutor_special_attendances';

    protected $fillable = [
        'classroom_id',
        'reg_no',
        'date',
        'hours',
        'category',
        'reason',
        'source',
        'document_path',
        'recorded_by'
    ];

    protected $casts = [
        'date' => 'date',
        'hours' => 'float',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }
}
