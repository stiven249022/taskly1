<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\VerifyEmailNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'role',
        'status',
        'email_verified_at',
        'password',
        'google_id',
        'profile_photo_path',
        'dark_mode',
        'email_notifications',
        'push_notifications',
        'task_reminders',
        'project_deadlines',
        'exam_reminders',
        'reminder_frequency',
        'theme',
        'timezone',
        'language',
        'bio',
        'phone',
        'location',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dark_mode' => 'boolean',
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'task_reminders' => 'boolean',
            'project_deadlines' => 'boolean',
            'exam_reminders' => 'boolean',
            'status' => 'string',
        ];
    }

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            // Verificar si el archivo existe
            if (Storage::disk('public')->exists($this->profile_photo_path)) {
                // Extraer el nombre del archivo de la ruta
                $filename = basename($this->profile_photo_path);
                return route('profile.photo', $filename);
            } else {
                // Si el archivo no existe, limpiar la referencia
                $this->profile_photo_path = null;
                $this->save();
            }
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=7F9CF5&background=EBF4FF';
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    // Relaciones para profesores
    public function assignedStudents()
    {
        return $this->hasMany(TeacherStudentAssignment::class, 'teacher_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'teacher_student_assignments', 'teacher_id', 'student_id')
                    ->withPivot('course_id', 'class_name', 'status')
                    ->withTimestamps();
    }

    // Relaciones para estudiantes
    public function assignedTeachers()
    {
        return $this->hasMany(TeacherStudentAssignment::class, 'student_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_student_assignments', 'student_id', 'teacher_id')
                    ->withPivot('course_id', 'class_name', 'status')
                    ->withTimestamps();
    }

    /**
     * Verificar si el usuario es estudiante
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Verificar si el usuario es profesor
     */
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    /**
     * Verificar si el usuario es administrador
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Verificar si el usuario tiene alguno de los roles especificados
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Verificar si el usuario está pendiente de autorización
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Verificar si el usuario está activo
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Verificar si el usuario está rechazado
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    

    /**
     * Calcular el promedio general del usuario basado en tareas y proyectos calificados
     */
    public function getAverageGradeAttribute(): float
    {
        // Obtener todas las calificaciones de tareas
        $taskGrades = $this->tasks()->whereNotNull('grade')->pluck('grade');
        
        // Obtener todas las calificaciones de proyectos
        $projectGrades = $this->projects()->whereNotNull('grade')->pluck('grade');
        
        // Combinar todas las calificaciones
        $allGrades = $taskGrades->merge($projectGrades);
        
        // Calcular y retornar el promedio, o 0 si no hay calificaciones
        return $allGrades->count() > 0 ? round($allGrades->avg(), 1) : 0.0;
    }

    public function getDisplayNameAttribute(): string
    {
        $name = trim((string) $this->name);
        $last = trim((string) $this->last_name);
        if ($last === '') {
            return $name;
        }
        $normalizedName = preg_replace('/\s+/', ' ', $name);
        $normalizedLast = preg_replace('/\s+/', ' ', $last);
        if (stripos($normalizedName, $normalizedLast) !== false) {
            return $normalizedName;
        }
        return trim($normalizedName . ' ' . $normalizedLast);
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification());
    }
}
