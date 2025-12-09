<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tags = Tag::where('user_id', Auth::id())
            ->withCount(['tasks', 'projects', 'reminders'])
            ->paginate(10);

        return view('etiquetas.index', compact('tags'));
    }

    public function create()
    {
        return view('etiquetas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'color' => 'required|string|max:7'
        ]);

        $tag = Tag::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'color' => $validated['color']
        ]);

        return redirect()->route('etiquetas.index')
            ->with('success', 'Etiqueta creada exitosamente.');
    }

    public function show(Tag $tag)
    {
        $this->authorize('view', $tag);

        $tag->load(['tasks', 'projects', 'reminders']);

        return view('etiquetas.show', compact('tag'));
    }

    public function edit(Tag $tag)
    {
        $this->authorize('update', $tag);

        return view('etiquetas.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $this->authorize('update', $tag);

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'color' => 'required|string|max:7'
        ]);

        $tag->update($validated);

        return redirect()->route('etiquetas.index')
            ->with('success', 'Etiqueta actualizada exitosamente.');
    }

    public function destroy(Tag $tag)
    {
        $this->authorize('delete', $tag);

        // Desvincular la etiqueta de todas las relaciones
        $tag->tasks()->detach();
        $tag->projects()->detach();
        $tag->reminders()->detach();

        $tag->delete();

        return redirect()->route('etiquetas.index')
            ->with('success', 'Etiqueta eliminada exitosamente.');
    }
} 