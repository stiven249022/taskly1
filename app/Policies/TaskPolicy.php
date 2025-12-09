<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Task $task): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Task $task): bool
    {
        return method_exists($user, 'hasRole') && !$user->hasRole('student');
    }

    public function submit(User $user, Task $task): bool
    {
        return true;
    }

    public function delete(User $user, Task $task): bool
    {
        return true;
    }

    public function restore(User $user, Task $task): bool
    {
        return true;
    }

    public function forceDelete(User $user, Task $task): bool
    {
        return true;
    }
}
