<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'title',
        'description',
        'due_date',
        'status'
    ];

    protected $casts = [
        'due_date' => 'datetime'
    ];

    /**
     * Obtiene el usuario propietario del recordatorio.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene el curso al que pertenece el recordatorio.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Obtiene las etiquetas asociadas al recordatorio.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Verifica si el recordatorio está vencido.
     */
    public function isOverdue(): bool
    {
        return $this->due_date->isPast() && $this->status !== 'completed';
    }

    /**
     * Verifica si el recordatorio está pendiente.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Verifica si el recordatorio está completado.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
} 