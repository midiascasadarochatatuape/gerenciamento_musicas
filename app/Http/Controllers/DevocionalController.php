<?php

namespace App\Http\Controllers;

use App\Models\Devocional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DevocionalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $devocionais = Devocional::with('user')
            ->when(request('search'), function($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                           ->orWhere('content', 'like', "%{$search}%");
            })
            ->when(request('status'), function($query, $status) {
                if ($status === 'published') {
                    return $query->where('is_published', true);
                } elseif ($status === 'draft') {
                    return $query->where('is_published', false);
                }
            })
            ->orderBy('devotional_date', 'desc')
            ->paginate(10);

        return view('devocionais.index', compact('devocionais'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $this->authorize('create', Devocional::class);
        return view('devocionais.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $this->authorize('create', Devocional::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bible_references' => 'nullable|array',
            'devotional_date' => 'required|date',
            'is_published' => 'boolean'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('devocionais', 'public');
        }

        $devocional = Devocional::create([
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'image' => $imagePath,
            'bible_references' => $validated['bible_references'] ?? [],
            'devotional_date' => $validated['devotional_date'],
            'is_published' => $validated['is_published'] ?? false,
            'user_id' => auth()->id()
        ]);

        return redirect()->route('devocionais.show', $devocional)
                        ->with('success', 'Devocional criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Devocional $devocional)
    {
        $devocional->incrementViews();
        return view('devocionais.show', compact('devocional'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Devocional $devocional)
    {
        // $this->authorize('update', $devocional);
        return view('devocionais.edit', compact('devocional'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Devocional $devocional)
    {
        // $this->authorize('update', $devocional);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bible_references' => 'nullable|array',
            'devotional_date' => 'required|date',
            'is_published' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            if ($devocional->image) {
                Storage::disk('public')->delete($devocional->image);
            }
            $validated['image'] = $request->file('image')->store('devocionais', 'public');
        }

        $validated['bible_references'] = $validated['bible_references'] ?? [];
        $validated['is_published'] = $validated['is_published'] ?? false;

        $devocional->update($validated);

        return redirect()->route('devocionais.show', $devocional)
                        ->with('success', 'Devocional atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Devocional $devocional)
    {
        // $this->authorize('delete', $devocional);

        if ($devocional->image) {
            Storage::disk('public')->delete($devocional->image);
        }

        $devocional->delete();

        return redirect()->route('devocionais.index')
                        ->with('success', 'Devocional excluído com sucesso!');
    }

    /**
     * Display published devotionals for public view
     */
    public function publicIndex()
    {
        $devocionais = Devocional::published()
            ->with('user')
            ->when(request('search'), function($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                           ->orWhere('content', 'like', "%{$search}%");
            })
            ->orderBy('devotional_date', 'desc')
            ->paginate(6);

        return view('devocionais.public-index', compact('devocionais'));
    }

    /**
     * Show a published devotional
     */
    public function publicShow(Devocional $devocional)
    {
        if (!$devocional->is_published) {
            abort(404);
        }

        $devocional->incrementViews();

        $recentDevocionais = Devocional::published()
            ->where('id', '!=', $devocional->id)
            ->recent(5)
            ->get();

        return view('devocionais.public-show', compact('devocional', 'recentDevocionais'));
    }
}
