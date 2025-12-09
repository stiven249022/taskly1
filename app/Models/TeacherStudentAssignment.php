<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherStudentAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'student_id',
        'course_id',
        'class_name',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    // Relación con el profesor
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Relación con el estudiante
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Relación con el curso
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Scope para asignaciones activas
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope para un profesor específico
    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }
}