<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\Course;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Reminder::with('course')
            ->where('user_id', auth()->id());

        // Filtrar por curso si se especifica
        if ($request->has('course') && $request->course !== '') {
            $query->where('course_id', $request->course);
        }

        // Filtrar por estado si se especifica
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $reminders = $query->orderBy('due_date')->paginate(10);
        $courses = Course::where('user_id', auth()->id())->get();
            
        return view('reminders.index', compact('reminders', 'courses'));
    }

    public function create()
    {
        $courses = Course::where('user_id', auth()->id())->get();
        return view('reminders.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,completed',
            'course_id' => 'required|exists:courses,id'
        ]);

        $reminder = Reminder::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'due_date' => $validated['due_date'],
            'status' => $validated['status'],
            'course_id' => $validated['course_id']
        ]);

        return redirect()->route('reminders.index')
            ->with('success', 'Recordatorio creado exitosamente.');
    }

    public function show(Reminder $reminder)
    {
        $this->authorize('view', $reminder);
        return view('reminders.show', compact('reminder'));
    }

    public function edit(Reminder $reminder)
    {
        $this->authorize('update', $reminder);
        $courses = Course::where('user_id', auth()->id())->get();
        return view('reminders.edit', compact('reminder', 'courses'));
    }

    public function update(Request $request, Reminder $reminder)
    {
        $this->authorize('update', $reminder);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,completed',
            'course_id' => 'required|exists:courses,id'
        ]);

        $reminder->update($validated);

        return redirect()->route('reminders.index')
            ->with('success', 'Recordatorio actualizado exitosamente.');
    }

    public function destroy(Reminder $reminder)
    {
        try {
            $this->authorize('delete', $reminder);
            $reminderTitle = $reminder->title;
            $reminder->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '¡Recordatorio eliminado exitosamente! El recordatorio "' . $reminderTitle . '" ha sido removido de tu lista.'
                ]);
            }

            return redirect()->route('reminders.index')
                ->with('success', '¡Recordatorio eliminado exitosamente! El recordatorio "' . $reminderTitle . '" ha sido removido de tu lista.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el recordatorio. Por favor, inténtalo de nuevo.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error al eliminar el recordatorio. Por favor, inténtalo de nuevo.');
        }
    }

    public function toggleStatus(Reminder $reminder)
    {
        try {
            $this->authorize('update', $reminder);

            $oldStatus = $reminder->status;
            $reminder->status = $reminder->status === 'completed' ? 'pending' : 'completed';
            $reminder->save();

            $message = $reminder->status === 'completed' 
                ? '¡Excelente! El recordatorio "' . $reminder->title . '" ha sido marcado como completado.'
                : 'El recordatorio "' . $reminder->title . '" ha sido marcado como pendiente.';

            return response()->json([
                'success' => true,
                'status' => $reminder->status,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado del recordatorio. Por favor, inténtalo de nuevo.'
            ], 500);
        }
    }
} 