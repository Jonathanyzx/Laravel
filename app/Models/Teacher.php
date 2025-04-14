<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'teacher_id',
        'name',
        'email',
        'phone',
        'address',
        'qualification',
        'specialization',
        'status',
        'profile_photo',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'teacher_id', 'user_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => '<span class="badge bg-success">Active</span>',
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'inactive' => '<span class="badge bg-danger">Inactive</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }
} 