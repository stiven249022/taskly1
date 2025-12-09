<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\TaskFileController;
use App\Http\Controllers\ProjectFileController;
use App\Http\Controllers\ProjectTaskController;
use App\Http\Controllers\SearchController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-dark-mode', function () {
    return view('test-dark-mode');
})->name('test.dark-mode');

// Rutas públicas para términos y políticas
Route::get('/terms', function () {
    return view('legal.terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('legal.privacy');
})->name('privacy');

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->hasRole('teacher')) {
        return redirect()->route('teacher.dashboard');
    } elseif ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    
    // Para estudiantes, usar el DashboardController
    return app(DashboardController::class)->index();
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Búsqueda global
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // Rutas de Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/preferences', [ProfileController::class, 'updatePreferences'])->name('profile.preferences');
    Route::delete('/profile/photo', [ProfileController::class, 'deleteProfilePhoto'])->name('profile.delete-photo');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Ruta para servir imágenes de perfil
    Route::get('/profile-photo/{filename}', function($filename) {
        $path = storage_path('app/public/profile-photos/' . $filename);
        if (file_exists($path)) {
            return response()->file($path);
        }
        abort(404);
    })->name('profile.photo');
    
    // Rutas de Tareas
    Route::resource('tasks', TaskController::class)->middleware('validate.request');
    Route::patch('/tasks/{task}/toggle-status', [TaskController::class, 'toggleStatus'])->name('tasks.toggle-status');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    
    // Rutas de archivos de tareas
    Route::post('/tasks/{task}/upload-file', [TaskFileController::class, 'upload'])->name('task-files.upload');
    Route::get('/tasks/{task}/view-file', [TaskFileController::class, 'view'])->name('task-files.view');
    Route::get('/tasks/{task}/download-file', [TaskFileController::class, 'download'])->name('task-files.download');
    Route::delete('/tasks/{task}/delete-file', [TaskFileController::class, 'delete'])->name('task-files.delete');
    Route::post('/tasks/{task}/grade', [TaskFileController::class, 'grade'])->name('task-files.grade');
    // Material de apoyo tareas
    Route::get('/tasks/{task}/support/view', [TaskFileController::class, 'viewSupport'])->name('task-files.support.view');
    Route::get('/tasks/{task}/support/download', [TaskFileController::class, 'downloadSupport'])->name('task-files.support.download');
    
    // Rutas de Proyectos
    Route::resource('projects', ProjectController::class)->middleware('validate.request');
    Route::patch('/projects/{project}/toggle-status', [ProjectController::class, 'toggleStatus'])->name('projects.toggle-status');
    Route::patch('/projects/{project}/update-progress', [ProjectController::class, 'updateProgress'])->name('projects.update-progress');
    
            // Rutas de archivos de proyectos
            Route::post('/projects/{project}/upload-file', [ProjectFileController::class, 'upload'])->name('project-files.upload');
            Route::get('/projects/{project}/view-file', [ProjectFileController::class, 'view'])->name('project-files.view');
            Route::get('/projects/{project}/download-file', [ProjectFileController::class, 'download'])->name('project-files.download');
            Route::delete('/projects/{project}/delete-file', [ProjectFileController::class, 'delete'])->name('project-files.delete');
            Route::post('/projects/{project}/grade', [ProjectFileController::class, 'grade'])->name('project-files.grade');
            Route::post('/projects/{project}/update-progress-file', [ProjectFileController::class, 'updateProgress'])->name('project-files.update-progress');
    // Material de apoyo proyectos
    Route::get('/projects/{project}/support/view', [ProjectFileController::class, 'viewSupport'])->name('project-files.support.view');
    Route::get('/projects/{project}/support/download', [ProjectFileController::class, 'downloadSupport'])->name('project-files.support.download');

            // Rutas de subtareas de proyectos
            Route::get('/projects/{project}/tasks', [ProjectTaskController::class, 'index'])->name('project-tasks.index');
            Route::get('/projects/{project}/tasks/create', [ProjectTaskController::class, 'create'])->name('project-tasks.create');
            Route::post('/projects/{project}/tasks', [ProjectTaskController::class, 'store'])->name('project-tasks.store');
            Route::get('/projects/{project}/tasks/{projectTask}', [ProjectTaskController::class, 'show'])->name('project-tasks.show');
            Route::get('/projects/{project}/tasks/{projectTask}/edit', [ProjectTaskController::class, 'edit'])->name('project-tasks.edit');
            Route::put('/projects/{project}/tasks/{projectTask}', [ProjectTaskController::class, 'update'])->name('project-tasks.update');
            Route::delete('/projects/{project}/tasks/{projectTask}', [ProjectTaskController::class, 'destroy'])->name('project-tasks.destroy');
            Route::post('/projects/{project}/tasks/{projectTask}/toggle-status', [ProjectTaskController::class, 'toggleStatus'])->name('project-tasks.toggle-status');
            Route::post('/projects/{project}/tasks/{projectTask}/upload-file', [ProjectTaskController::class, 'uploadFile'])->name('project-tasks.upload-file');
            Route::delete('/projects/{project}/tasks/{projectTask}/delete-file', [ProjectTaskController::class, 'deleteFile'])->name('project-tasks.delete-file');
            Route::get('/projects/{project}/tasks/{projectTask}/download-file', [ProjectTaskController::class, 'downloadFile'])->name('project-tasks.download-file');
            Route::get('/projects/{project}/tasks/{projectTask}/view-file', [ProjectTaskController::class, 'viewFile'])->name('project-tasks.view-file');
            Route::post('/projects/{project}/tasks/{projectTask}/grade', [ProjectTaskController::class, 'grade'])->name('project-tasks.grade');
            Route::post('/projects/{project}/tasks/reorder', [ProjectTaskController::class, 'reorder'])->name('project-tasks.reorder');
    
    // Rutas de Cursos
    Route::resource('courses', CourseController::class)->middleware('validate.request');
    
    // Rutas de Recordatorios
    Route::resource('reminders', ReminderController::class)->middleware('validate.request');
    Route::patch('/reminders/{reminder}/toggle-status', [ReminderController::class, 'toggleStatus'])->name('reminders.toggle-status');
    
    // Rutas del Calendario
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
    Route::post('/calendar/events', [CalendarController::class, 'store'])->name('calendar.store');
    Route::put('/calendar/events/{event}', [CalendarController::class, 'update'])->name('calendar.update');
    Route::delete('/calendar/events/{event}', [CalendarController::class, 'destroy'])->name('calendar.destroy');
    
    // Rutas de Notificaciones
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/unread', [NotificationController::class, 'getUnread'])->name('notifications.unread');
    Route::get('/notifications/upcoming-events', [NotificationController::class, 'getUpcomingEvents'])->name('notifications.upcoming-events');
    Route::get('/notifications/upcoming-events/view', [NotificationController::class, 'showUpcomingEvents'])->name('notifications.upcoming-events.view');
    Route::get('/notifications/settings', [NotificationController::class, 'settings'])->name('notifications.settings');
    Route::put('/notifications/settings', [NotificationController::class, 'updateSettings'])->name('notifications.settings.update');
    
    // Rutas de Configuración
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/general', [SettingsController::class, 'general'])->name('settings.general');
    Route::put('/settings/general', [SettingsController::class, 'updateGeneral'])->name('settings.general.update');
    Route::get('/settings/notifications', [SettingsController::class, 'notifications'])->name('settings.notifications');
    Route::put('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications.update');
    Route::get('/settings/security', [SettingsController::class, 'security'])->name('settings.security');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::get('/settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::post('/settings/preferences', [SettingsController::class, 'updatePreferences'])->name('settings.preferences');
    Route::get('/settings/export-data', [SettingsController::class, 'exportData'])->name('settings.export-data');
    Route::delete('/settings/delete-account', [SettingsController::class, 'deleteAccount'])->name('settings.delete-account');
    
    // Rutas de prueba para notificaciones
    Route::get('/test-notifications', function() {
        return view('test-notifications');
    })->name('test.notifications');

    // Ruta de prueba para eventos próximos
    Route::get('/test/upcoming-events', function() {
        $user = auth()->user();
        
        // Obtener conteos de eventos próximos
        $tasks = \App\Models\Task::where('user_id', $user->id)
            ->where('status', '!=', 'completed')
            ->whereDate('due_date', '>=', now())
            ->whereDate('due_date', '<=', now()->addDays(3))
            ->count();
        
        $projects = \App\Models\Project::where('user_id', $user->id)
            ->where('status', '!=', 'completed')
            ->whereDate('end_date', '>=', now())
            ->whereDate('end_date', '<=', now()->addDays(7))
            ->count();
        
        $reminders = \App\Models\Reminder::where('user_id', $user->id)
            ->whereDate('due_date', '>=', now())
            ->whereDate('due_date', '<=', now()->addDays(2))
            ->count();
        
        // Llamar al método del controlador para crear notificaciones
        $controller = new \App\Http\Controllers\NotificationController();
        $response = $controller->getUpcomingEvents();
        $data = json_decode($response->getContent(), true);
        
        return view('test-upcoming-events', [
            'events' => $data['events'] ?? [],
            'count' => $data['count'] ?? 0,
            'tasksCount' => $tasks,
            'projectsCount' => $projects,
            'remindersCount' => $reminders
        ]);
    })->name('test.upcoming-events');

    Route::post('/test/success', function() {
        return redirect()->back()->with('success', '¡Esta es una notificación de éxito!');
    })->name('test.success');

    Route::post('/test/error', function() {
        return redirect()->back()->with('error', '¡Esta es una notificación de error!');
    })->name('test.error');

    Route::post('/test/warning', function () {
        return redirect()->back()->with('warning', '¡Esta es una notificación de advertencia!');
    })->name('test.warning');
});

// Rutas específicas para profesores
Route::middleware(['auth', 'verified', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('/students', [TeacherDashboardController::class, 'students'])->name('students');
    Route::get('/tasks', [TeacherDashboardController::class, 'tasks'])->name('tasks');
    Route::get('/projects', [TeacherDashboardController::class, 'projects'])->name('projects');
    Route::get('/grades', [TeacherDashboardController::class, 'grades'])->name('grades');
    Route::get('/analytics', [TeacherDashboardController::class, 'analytics'])->name('analytics');
    Route::get('/analytics/download', [TeacherDashboardController::class, 'downloadReport'])->name('analytics.download');
    Route::get('/assign-students', [TeacherDashboardController::class, 'assignStudents'])->name('assign-students');
    Route::post('/assign-students', [TeacherDashboardController::class, 'storeStudentAssignment'])->name('assign-students.store');
    Route::delete('/assign-students/{assignment}', [TeacherDashboardController::class, 'removeStudentAssignment'])->name('assign-students.remove');
    Route::patch('/assign-students/{assignment}/class-name', [TeacherDashboardController::class, 'updateClassName'])->name('assign-students.update-class-name');
    Route::get('/create-task', [TeacherDashboardController::class, 'createTask'])->name('create-task');
    Route::post('/create-task', [TeacherDashboardController::class, 'storeTask'])->name('create-task.store');
    Route::get('/create-project', [TeacherDashboardController::class, 'createProject'])->name('create-project');
    Route::post('/create-project', [TeacherDashboardController::class, 'storeProject'])->name('create-project.store');
    Route::get('/students/{student}/courses', [TeacherDashboardController::class, 'getStudentCourses'])->name('students.courses');
    Route::get('/students/{student}/details', [TeacherDashboardController::class, 'getStudentDetails'])->whereNumber('student')->name('students.details');
    Route::post('/tasks/{task}/grade', [TeacherDashboardController::class, 'gradeTask'])->name('tasks.grade');
    Route::post('/projects/{project}/grade', [TeacherDashboardController::class, 'gradeProject'])->name('projects.grade');
    
    // Ruta para ver tareas de estudiantes
    Route::get('/student-tasks', [TaskController::class, 'teacherIndex'])->name('student-tasks');
    
    // Ruta para ver proyectos de estudiantes
    Route::get('/student-projects', [ProjectController::class, 'teacherIndex'])->name('student-projects');
});

// Rutas específicas para administradores
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::get('/teachers', [AdminDashboardController::class, 'teachers'])->name('teachers');
    Route::get('/teachers/{teacher}', [AdminDashboardController::class, 'showTeacher'])
        ->whereNumber('teacher')
        ->name('teachers.show');
    Route::get('/teachers/{teacher}/download-report', [AdminDashboardController::class, 'downloadTeacherReport'])->name('teachers.download-report');
    Route::post('/users/{user}/assign-role', [AdminDashboardController::class, 'assignRole'])->name('users.assign-role');
    Route::post('/users/{user}/approve', [AdminDashboardController::class, 'approveUser'])->name('users.approve');
    Route::post('/users/{user}/reject', [AdminDashboardController::class, 'rejectUser'])->name('users.reject');
    Route::get('/teachers/create', [AdminDashboardController::class, 'createTeacher'])->name('teachers.create');
    Route::post('/teachers', [AdminDashboardController::class, 'storeTeacher'])->name('teachers.store');
    
    // Rutas adicionales para funcionalidades administrativas
    Route::get('/reports', function() {
        return view('admin.reports');
    })->name('reports');
    
    // Rutas de reportes
    Route::get('/reports/users', [AdminDashboardController::class, 'downloadUsersReport'])->name('reports.users');
    Route::get('/reports/teachers-performance', [AdminDashboardController::class, 'downloadTeachersPerformanceReport'])->name('reports.teachers-performance');
    Route::get('/reports/system-usage', [AdminDashboardController::class, 'downloadSystemUsageReport'])->name('reports.system-usage');
    
    Route::get('/settings', [\App\Http\Controllers\AdminSettingsController::class, 'index'])->name('settings');
    Route::post('/settings/general', [\App\Http\Controllers\AdminSettingsController::class, 'updateGeneral'])->name('settings.general');
    Route::post('/settings/security', [\App\Http\Controllers\AdminSettingsController::class, 'updateSecurity'])->name('settings.security');
    Route::post('/settings/notifications', [\App\Http\Controllers\AdminSettingsController::class, 'updateNotifications'])->name('settings.notifications');
    Route::get('/settings/database/info', [\App\Http\Controllers\AdminSettingsController::class, 'getDatabaseInfo'])->name('settings.database.info');
    Route::post('/settings/database/backup', [\App\Http\Controllers\AdminSettingsController::class, 'backupDatabase'])->name('settings.database.backup');
    Route::post('/settings/cache/clear', [\App\Http\Controllers\AdminSettingsController::class, 'clearCache'])->name('settings.cache.clear');
    
    Route::get('/logs', [\App\Http\Controllers\AdminLogsController::class, 'index'])->name('logs');
    Route::get('/logs/download', [\App\Http\Controllers\AdminLogsController::class, 'download'])->name('logs.download');
    Route::post('/logs/clear', [\App\Http\Controllers\AdminLogsController::class, 'clear'])->name('logs.clear');
    Route::get('/logs/refresh', [\App\Http\Controllers\AdminLogsController::class, 'refresh'])->name('logs.refresh');
    
    // Gestión de asignaciones profesor-estudiante
    Route::get('/assignments', [AdminDashboardController::class, 'assignments'])->name('assignments');
    Route::post('/assignments', [AdminDashboardController::class, 'storeAssignment'])->name('assignments.store');
    Route::patch('/assignments/{assignment}/toggle', [AdminDashboardController::class, 'toggleAssignment'])->name('assignments.toggle');
    Route::delete('/assignments/{assignment}', [AdminDashboardController::class, 'destroyAssignment'])->name('assignments.destroy');
});

// Rutas de verificación de email (requieren autenticación pero no verificación)
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');
    
    Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
        $user = \App\Models\User::find($id);
        
        if (!$user) {
            abort(404);
        }
        
        if (!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            abort(403);
        }
        
        if ($user->hasVerifiedEmail()) {
            return redirect('/dashboard')->with('success', 'Tu email ya está verificado.');
        }
        
        $user->markEmailAsVerified();
        
        return redirect('/dashboard')->with('success', '¡Tu email ha sido verificado exitosamente!');
    })->name('verification.verify');
    
    Route::post('/email/verification-notification', function () {
        $user = auth()->user();
        
        if ($user->hasVerifiedEmail()) {
            return redirect('/dashboard')->with('info', 'Tu email ya está verificado.');
        }
        
        $user->sendEmailVerificationNotification();
        
        return redirect()->back()->with('success', 'Email de verificación enviado.');
    })->name('verification.send');
});

require __DIR__.'/auth.php';
