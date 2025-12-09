<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'teacher_id',
        'course_id',
        'title',
        'description',
        'type',
        'start_date',
        'due_date',
        'priority',
        'status',
        'grade',
        'feedback',
        'graded_at',
        'graded_by',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'submitted_at'
        ,
        'support_file_path',
        'support_file_name',
        'support_file_type',
        'support_file_size'
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'start_date' => 'datetime',
        'graded_at' => 'datetime',
        'submitted_at' => 'datetime',
        'grade' => 'decimal:2'
    ];

    /**
     * Obtiene el usuario propietario de la tarea.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene el curso al que pertenece la tarea.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Obtiene el profesor que asignó la tarea.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Obtiene las etiquetas asociadas a la tarea.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Obtiene el profesor que calificó la tarea.
     */
    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    /**
     * Verifica si la tarea está vencida.
     */
    public function isOverdue(): bool
    {
        return $this->due_date->isPast() && $this->status !== 'completed';
    }

    /**
     * Verifica si la tarea está pendiente.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Verifica si la tarea está completada.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Verifica si la tarea tiene un archivo subido.
     */
    public function hasFile(): bool
    {
        return !is_null($this->file_path);
    }

    /**
     * Obtiene la URL del archivo.
     */
    public function getFileUrl(): string
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : '';
    }

    /**
     * Obtiene el tamaño del archivo formateado.
     */
    public function getFormattedFileSize(): string
    {
        if (!$this->file_size) {
            return '0 B';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    // Material de apoyo
    public function hasSupportFile(): bool
    {
        return !is_null($this->support_file_path);
    }

    public function getSupportFileUrl(): string
    {
        return $this->support_file_path ? asset('storage/' . $this->support_file_path) : '';
    }

    public function getFormattedSupportFileSize(): string
    {
        if (!$this->support_file_size) {
            return '0 B';
        }

        $bytes = $this->support_file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Verifica si la tarea ha sido entregada (tiene archivo).
     */
    public function isSubmitted(): bool
    {
        return $this->hasFile() && !is_null($this->submitted_at);
    }
} 