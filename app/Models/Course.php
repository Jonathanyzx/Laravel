<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_code',
        'name',
        'description',
        'credits',
        'duration_months',
        'fee',
        'is_active',
        'teacher_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'fee' => 'decimal:2',
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments')
            ->withPivot('enrollment_date', 'status', 'grade', 'notes')
            ->withTimestamps();
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
