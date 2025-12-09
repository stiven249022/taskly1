<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'code',
        'description',
        'color',
        'semester',
        'professor',
        'schedule',
        'credits'
    ];

    protected $casts = [
        'credits' => 'integer'
    ];

    /**
     * Obtiene el usuario propietario del curso.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene las tareas asociadas al curso.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Obtiene los proyectos asociados al curso.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Obtiene los recordatorios asociados al curso.
     */
    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }
} 