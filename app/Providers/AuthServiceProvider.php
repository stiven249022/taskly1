<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Project;
use App\Models\Reminder;
use App\Models\Task;
use App\Models\Tag;
use App\Policies\CoursePolicy;
use App\Policies\ProjectPolicy;
use App\Policies\ReminderPolicy;
use App\Policies\TaskPolicy;
use App\Policies\TagPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Task::class => TaskPolicy::class,
        Project::class => ProjectPolicy::class,
        Reminder::class => ReminderPolicy::class,
        Course::class => CoursePolicy::class,
        Tag::class => TagPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
} 