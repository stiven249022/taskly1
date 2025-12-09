<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'order',
        'due_date',
        'priority',
        'notes',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'submitted_at',
        'grade',
        'feedback',
        'graded_at',
        'graded_by'
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'grade' => 'decimal:2'
    ];

    /**
     * Obtiene el proyecto al que pertenece la subtarea.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Obtiene el usuario que calificó la subtarea.
     */
    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    /**
     * Verifica si la subtarea está pendiente.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Verifica si la subtarea está en progreso.
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Verifica si la subtarea está completada.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Verifica si la subtarea tiene un archivo subido.
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

    /**
     * Verifica si la subtarea ha sido entregada (tiene archivo).
     */
    public function isSubmitted(): bool
    {
        return $this->hasFile() && !is_null($this->submitted_at);
    }

    /**
     * Obtiene el color de prioridad.
     */
    public function getPriorityColor(): string
    {
        switch ($this->priority) {
            case 5:
                return 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200';
            case 4:
                return 'bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200';
            case 3:
                return 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200';
            case 2:
                return 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200';
            default:
                return 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200';
        }
    }

    /**
     * Obtiene el texto de prioridad.
     */
    public function getPriorityText(): string
    {
        switch ($this->priority) {
            case 5:
                return 'Crítica';
            case 4:
                return 'Alta';
            case 3:
                return 'Media';
            case 2:
                return 'Baja';
            default:
                return 'Muy Baja';
        }
    }

    /**
     * Obtiene el color de estado.
     */
    public function getStatusColor(): string
    {
        switch ($this->status) {
            case 'completed':
                return 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200';
            case 'in_progress':
                return 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200';
            default:
                return 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200';
        }
    }

    /**
     * Obtiene el texto de estado.
     */
    public function getStatusText(): string
    {
        switch ($this->status) {
            case 'completed':
                return 'Completada';
            case 'in_progress':
                return 'En Progreso';
            default:
                return 'Pendiente';
        }
    }

    /**
     * Verifica si la subtarea está vencida.
     */
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && !$this->isCompleted();
    }
}