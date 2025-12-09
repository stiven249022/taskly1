<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'teacher_id',
        'course_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'progress',
        'priority',
        'reminder',
        'reminder_days',
        'reminder_time',
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
        'start_date' => 'date',
        'end_date' => 'date',
        'progress' => 'integer',
        'reminder' => 'boolean',
        'graded_at' => 'datetime',
        'submitted_at' => 'datetime',
        'grade' => 'decimal:2'
    ];

    /**
     * Obtiene el usuario propietario del proyecto.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene el curso al que pertenece el proyecto.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Obtiene el profesor que supervisa el proyecto.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Obtiene las etiquetas asociadas al proyecto.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Obtiene el profesor que calificó el proyecto.
     */
    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    /**
     * Obtiene las subtareas del proyecto.
     */
    public function projectTasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class)->orderBy('order');
    }

    /**
     * Verifica si el proyecto está vencido.
     */
    public function isOverdue(): bool
    {
        return $this->end_date->isPast() && $this->status !== 'completed';
    }

    /**
     * Verifica si el proyecto está activo.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Verifica si el proyecto está completado.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Actualiza el progreso del proyecto.
     */
    public function updateProgress(int $progress): void
    {
        $this->progress = max(0, min(100, $progress));
        
        if ($this->progress === 100) {
            $this->status = 'completed';
        }
        
        $this->save();
    }

    /**
     * Calcula y actualiza el progreso del proyecto basado en las subtareas completadas.
     */
    public function calculateProgress(): void
    {
        $totalTasks = $this->projectTasks()->count();
        
        if ($totalTasks === 0) {
            $this->progress = 0;
        } else {
            $completedTasks = $this->projectTasks()->where('status', 'completed')->count();
            $this->progress = round(($completedTasks / $totalTasks) * 100);
        }
        
        // Actualizar estado del proyecto
        if ($this->progress === 100) {
            $this->status = 'completed';
        } elseif ($this->progress > 0) {
            $this->status = 'active';
        }
        
        $this->save();
    }

    /**
     * Obtiene el progreso calculado sin guardar.
     */
    public function getCalculatedProgress(): int
    {
        $totalTasks = $this->projectTasks()->count();
        
        if ($totalTasks === 0) {
            return 0;
        }
        
        $completedTasks = $this->projectTasks()->where('status', 'completed')->count();
        return round(($completedTasks / $totalTasks) * 100);
    }

    /**
     * Obtiene estadísticas de las subtareas.
     */
    public function getTaskStats(): array
    {
        $tasks = $this->projectTasks();
        
        return [
            'total' => $tasks->count(),
            'completed' => $tasks->where('status', 'completed')->count(),
            'in_progress' => $tasks->where('status', 'in_progress')->count(),
            'pending' => $tasks->where('status', 'pending')->count(),
            'overdue' => $tasks->where('due_date', '<', now())
                ->where('status', '!=', 'completed')
                ->count()
        ];
    }

    /**
     * Verifica si el proyecto tiene un archivo subido.
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
     * Verifica si el proyecto ha sido entregado (tiene archivo).
     */
    public function isSubmitted(): bool
    {
        return $this->hasFile() && !is_null($this->submitted_at);
    }

    /**
     * Obtiene el color de la barra de progreso basado en el progreso.
     */
    public function getProgressColor(): string
    {
        if ($this->progress < 25) {
            return 'bg-red-500';
        } elseif ($this->progress < 50) {
            return 'bg-orange-500';
        } elseif ($this->progress < 75) {
            return 'bg-yellow-500';
        } else {
            return 'bg-green-500';
        }
    }

    /**
     * Obtiene el texto de estado del progreso.
     */
    public function getProgressText(): string
    {
        if ($this->progress === 0) {
            return 'No iniciado';
        } elseif ($this->progress < 25) {
            return 'Iniciando';
        } elseif ($this->progress < 50) {
            return 'En desarrollo';
        } elseif ($this->progress < 75) {
            return 'Avanzado';
        } elseif ($this->progress < 100) {
            return 'Casi terminado';
        } else {
            return 'Completado';
        }
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
} 