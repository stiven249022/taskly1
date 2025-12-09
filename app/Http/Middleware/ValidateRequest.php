<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidateRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Validaciones específicas según la ruta
        $route = $request->route();
        $routeName = $route ? $route->getName() : '';
        
        $validationRules = $this->getValidationRules($routeName, $request);
        
        if (!empty($validationRules)) {
            $validator = Validator::make($request->all(), $validationRules);
            
            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error de validación',
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }
        
        return $next($request);
    }
    
    /**
     * Obtener reglas de validación según la ruta
     */
    private function getValidationRules($routeName, $request)
    {
        $rules = [];
        
        switch ($routeName) {
            case 'tasks.store':
            case 'tasks.update':
                $rules = [
                    'title' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'course_id' => 'required|exists:courses,id',
                    'due_date' => 'required|date|after_or_equal:today',
                    'priority' => 'required|in:low,medium,high',
                    'type' => 'nullable|in:task,exam,reading,project'
                ];
                break;
                
            case 'projects.store':
            case 'projects.update':
                $rules = [
                    'name' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'course_id' => 'required|exists:courses,id',
                    'end_date' => 'required|date|after_or_equal:today',
                    'priority' => 'required|in:low,medium,high'
                ];
                break;
                
            case 'courses.store':
            case 'courses.update':
                $rules = [
                    'name' => 'required|string|max:255',
                    'code' => 'required|string|max:50',
                    'description' => 'nullable|string',
                    'color' => 'required|string|max:7',
                    'semester' => 'required|string|max:50',
                    'professor' => 'nullable|string|max:255',
                    'schedule' => 'nullable|string|max:255',
                    'credits' => 'required|integer|min:1|max:20'
                ];
                break;
                
            case 'reminders.store':
            case 'reminders.update':
                $rules = [
                    'title' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'course_id' => 'required|exists:courses,id',
                    'due_date' => 'required|date|after_or_equal:today',
                    'status' => 'required|in:pending,completed'
                ];
                break;
                
            case 'calendar.store':
            case 'calendar.update':
                $rules = [
                    'title' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'start' => 'required|date',
                    'end' => 'required|date|after_or_equal:start',
                    'type' => 'required|in:task,project,reminder',
                    'course_id' => 'nullable|exists:courses,id',
                    'priority' => 'nullable|in:low,medium,high'
                ];
                break;
        }
        
        return $rules;
    }
} 