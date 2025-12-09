<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'color'
    ];

    /**
     * Obtiene el usuario propietario de la etiqueta.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene las tareas asociadas a la etiqueta.
     */
    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class);
    }

    /**
     * Obtiene los proyectos asociados a la etiqueta.
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    /**
     * Obtiene los recordatorios asociados a la etiqueta.
     */
    public function reminders(): BelongsToMany
    {
        return $this->belongsToMany(Reminder::class);
    }
} 