<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\SongTutorial;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SongController extends Controller
{
    public function index(Request $request)
    {
        $query = Song::query();

        // Filtro por letra
        if ($request->filled('letter')) {
            $letter = $request->letter;
            if ($letter === '#') {
                $query->where('title', 'regexp', '^[0-9]');
            } else {
                $query->where('title', 'like', $letter . '%');
            }
        }

        // Filtro por categoria
        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category)
                  ->where('type', 'category');
            });
        }

        // Filtro por contexto (mantido para compatibilidade)
        if ($request->filled('context')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->context)
                  ->where('type', 'context');
            });
        }

        // Filtro por tipo
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtro por tom
        if ($request->filled('tone')) {
            $query->where('tone', $request->tone);
        }

        // Filtro por intensidade
        if ($request->filled('intensity')) {
            $query->where('intensity', $request->intensity);
        }

        // Filtro por compasso/tempo
        if ($request->filled('tempo')) {
            $query->where('measure', $request->tempo);
        }

        // Aplicar ordenação
        $sortBy = $request->get('sort', 'title');
        switch ($sortBy) {
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'times':
                $query->orderBy('times', 'desc');
                break;
            case 'times_asc':
                $query->orderBy('times', 'asc');
                break;
            case 'created_at':
                $query->orderBy('created_at', 'desc');
                break;
            case 'created_at_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'title':
            default:
                $query->orderBy('title', 'asc');
                break;
        }

        $songs = $query->where('status', 7)->paginate(10);

        // Manter os parâmetros da URL na paginação
        $songs->appends($request->query());

        $categories = Category::all();

        // Se for uma requisição AJAX, retornar JSON para scroll infinito
        if ($request->ajax()) {
            return response()->json([
                'songs' => $songs->items(),
                'hasMore' => $songs->hasMorePages(),
                'currentPage' => $songs->currentPage(),
                'totalSongs' => $songs->total()
            ]);
        }

        return view('songs.index', compact('songs', 'categories'));
    }

    public function searchPage(Request $request)
    {
        $query = Song::query();

        // Busca por texto (título, versão, referência bíblica)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('version', 'like', '%' . $searchTerm . '%')
                  ->orWhere('bible_reference', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filtro por categoria
        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category)
                  ->where('type', 'category');
            });
        }

        // Filtro por tipo
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtro por tom
        if ($request->filled('tone')) {
            $query->where('tone', $request->tone);
        }

        // Filtro por intensidade
        if ($request->filled('intensity')) {
            $query->where('intensity', $request->intensity);
        }

        // Filtro por compasso/tempo
        if ($request->filled('tempo')) {
            $query->where('measure', $request->tempo);
        }

        // Aplicar ordenação
        $sortBy = $request->get('sort', 'title');
        switch ($sortBy) {
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'times':
                $query->orderBy('times', 'desc');
                break;
            case 'times_asc':
                $query->orderBy('times', 'asc');
                break;
            case 'created_at':
                $query->orderBy('created_at', 'desc');
                break;
            case 'created_at_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'title':
            default:
                $query->orderBy('title', 'asc');
                break;
        }

        $songs = $query->where('status', 7)->paginate(10);
        $categories = Category::all();

        // Se for uma requisição AJAX, retornar JSON para scroll infinito
        if ($request->ajax()) {
            return response()->json([
                'songs' => $songs->items(),
                'hasMore' => $songs->hasMorePages(),
                'currentPage' => $songs->currentPage(),
                'totalSongs' => $songs->total()
            ]);
        }

        return view('songs.search', compact('songs', 'categories'));
    }

    public function show(Song $song)
    {
        // Carregar tutoriais junto com a música
        $song->load('tutorials');

        if ($song->status < 7) {
            return view('songs.show_suggestion', compact('song'));
        }
        return view('songs.show', compact('song'));
    }

    public function edit(Song $song)
    {
        $song->load('tutorials');
        $categories = Category::all();
        return view('songs.edit', compact('song', 'categories'));
    }

    public function update(Request $request, Song $song)
    {
        //dd($request->link_youtube);

        $this->authorize('update', $song);

        //dd($request);

        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'version' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'snippet' => 'nullable|string',
                'tone' => 'nullable|string|max:10',
                'tempo' => 'nullable|string|max:50',
                'measure' => 'nullable|string|max:50',
                'type' => 'nullable|in:hino,corinho,cantico,atual',
                'intensity' => 'nullable|in:lenta,media,rapida',
                'bible_reference' => 'nullable|string',
                'link_youtube' => 'nullable|url|max:255',
                'link_spotify' => 'nullable|url|max:255',
                'link_drive' => 'nullable|url|max:255',
                'lyrics' => 'nullable|string',
                'chords' => 'nullable|string',
                'status' => 'required|integer|between:1,7',
                'tutorials' => 'nullable|array',
                'tutorials.*.instrument' => 'nullable|in:Guitarra,Teclado,Violão,Bateria,Baixo,Sopro,Cordas',
                'tutorials.*.title' => 'nullable|string|max:255',
                'tutorials.*.url' => 'nullable|url|max:500'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('SongController update - Erro de validação', [
                'errors' => $e->errors()
            ]);
            throw $e;
        }        try {
            if ($request->hasFile('image')) {
                if ($song->image && file_exists(public_path($song->image))) {
                    unlink(public_path($song->image));
                }
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/image'), $imageName);
                $validatedData['image'] = '/storage/image/' . $imageName;
            }

            $song->update($validatedData);
            $song->categories()->sync($request->categories ?? []);

            // Atualizar tutoriais
            \Log::info('SongController update - Debug tutoriais', [
                'has_tutorials' => $request->has('tutorials'),
                'tutorials_data' => $request->get('tutorials'),
                'all_request' => $request->all()
            ]);

            if ($request->has('tutorials')) {
                // Remover tutoriais existentes
                $song->tutorials()->delete();

                // Adicionar novos tutoriais
                foreach ($request->tutorials as $tutorial) {
                    if (!empty($tutorial['instrument']) && !empty($tutorial['url'])) {
                        $song->tutorials()->create([
                            'instrument' => $tutorial['instrument'],
                            'title' => $tutorial['title'] ?? null,
                            'url' => $tutorial['url']
                        ]);
                    }
                }
            } else {
                // Se não há tutoriais no request, remover todos os existentes
                $song->tutorials()->delete();
            }            return redirect()->route('songs.show', $song)->with('success', 'Música atualizada com sucesso!');
        } catch (\Exception $e) {
            \Log::error('SongController update - Erro na atualização', [
                'song_id' => $song->id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'Erro ao atualizar música: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $categories = Category::all();
        return view('songs.create', compact('categories'));
    }

    public function suggest(Request $request)
    {
        $status = $request->get('status', 1);

        $query = Song::query();
        $query->where('status', $status);

        $songs = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('songs.suggest', compact('songs'));
    }

    public function createSuggestion()
    {
        return view('songs.create_suggestion');
    }

    public function store(Request $request)
    {
        //dd($request->chords);

        $this->authorize('create', Song::class);

        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Você precisa estar autenticado para criar uma música.');
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'version' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'snippet' => 'nullable|string',
            'tone' => 'nullable|string|max:255',
            'tempo' => 'nullable|string|max:255',
            'measure' => 'nullable|string|max:255',
            'type' => 'nullable|in:hino,corinho,cantico,atual',
            'intensity' => 'nullable|in:lenta,media,rapida',
            'bible_reference' => 'nullable|string',
            'link_youtube' => 'nullable|url|max:255',
            'link_spotify' => 'nullable|url|max:255',
            'link_drive' => 'nullable|url|max:255',
            'lyrics' => 'nullable|string',
            'chords' => 'nullable|string',
            'status' => 'required|integer|between:1,7',
            'tutorials' => 'nullable|array',
            'tutorials.*.instrument' => 'nullable|in:Guitarra,Teclado,Violão,Bateria,Baixo,Sopro,Cordas',
            'tutorials.*.title' => 'nullable|string|max:255',
            'tutorials.*.url' => 'nullable|url|max:500'
        ]);

        try {
            // Garantir que o id_user seja definido antes de criar o registro
            $validatedData['id_user'] = auth()->id();
            //$validatedData['status'] = 7;
            $validatedData['times'] = 0;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/image'), $imageName);
                $validatedData['image'] = '/storage/image/' . $imageName;
            }

            //dd(auth()->check(), auth()->id(), $validatedData);

            // Criar a música com todos os dados necessários
            $song = Song::create($validatedData);

            if ($request->has('categories')) {
                $song->categories()->sync($request->categories);
            }

            // Salvar tutoriais se fornecidos
            if ($request->has('tutorials')) {
                foreach ($request->tutorials as $tutorial) {
                    if (!empty($tutorial['instrument']) && !empty($tutorial['url'])) {
                        $song->tutorials()->create([
                            'instrument' => $tutorial['instrument'],
                            'title' => $tutorial['title'] ?? null,
                            'url' => $tutorial['url']
                        ]);
                    }
                }
            }

            // Se for uma sugestão (status = 1), redirecionar para a página de sugestões
            if ($validatedData['status'] == 1) {
                return redirect()->route('songs.suggest')
                    ->with('success', 'Sugestão "' . $song->title . '" enviada com sucesso!');
            }

            return redirect()->route('songs.show', $song)
                ->with('success', 'Música criada com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro ao criar música: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Erro ao criar música: ' . $e->getMessage());
        }
    }

    public function storeAndNew(Request $request)
    {
        $this->authorize('create', Song::class);

        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Você precisa estar autenticado para criar uma música.');
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'version' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'snippet' => 'nullable|string',
            'tone' => 'nullable|string|max:255',
            'tempo' => 'nullable|string|max:255',
            'measure' => 'nullable|string|max:255',
            'type' => 'nullable|in:hino,corinho,cantico,atual',
            'intensity' => 'nullable|in:lenta,media,rapida',
            'bible_reference' => 'nullable|string',
            'link_youtube' => 'nullable|url|max:255',
            'link_spotify' => 'nullable|url|max:255',
            'link_drive' => 'nullable|url|max:255',
            'lyrics' => 'nullable|string',
            'chords' => 'nullable|string',
            'status' => 'required|integer|between:1,7'
        ]);

        try {
            // Garantir que o id_user seja definido antes de criar o registro
            $validatedData['id_user'] = auth()->id();
            $validatedData['times'] = 0;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/image'), $imageName);
                $validatedData['image'] = '/storage/image/' . $imageName;
            }

            // Criar a música com todos os dados necessários
            $song = Song::create($validatedData);

            if ($request->has('categories')) {
                $song->categories()->sync($request->categories);
            }

            return redirect()->route('songs.create')
                ->with('success', 'Música "' . $song->title . '" criada com sucesso! Você pode adicionar uma nova música agora.');
        } catch (\Exception $e) {
            \Log::error('Erro ao criar música: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Erro ao criar música: ' . $e->getMessage());
        }
    }

    public function storeSuggestionAndNew(Request $request)
    {
        $this->authorize('create', Song::class);

        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Você precisa estar autenticado para criar uma sugestão.');
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'version' => 'nullable|string|max:255',
            'link_youtube' => 'nullable|url|max:255',
            'link_spotify' => 'nullable|url|max:255',
            'lyrics' => 'nullable|string',
        ]);

        try {
            // Garantir que seja sempre uma sugestão (status = 1)
            $validatedData['id_user'] = auth()->id();
            $validatedData['status'] = 1;
            $validatedData['times'] = 0;

            // Criar a sugestão com todos os dados necessários
            $song = Song::create($validatedData);

            return redirect()->route('songs.create.suggestion')
                ->with('success', 'Sugestão "' . $song->title . '" enviada com sucesso! Você pode adicionar uma nova sugestão agora.');
        } catch (\Exception $e) {
            \Log::error('Erro ao criar sugestão: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Erro ao enviar sugestão: ' . $e->getMessage());
        }
    }

    public function destroy(Song $song)
    {
        // Remove a imagem se existir
        if ($song->image && file_exists(public_path($song->image))) {
            unlink(public_path($song->image));
        }

        $song->delete();

        return redirect()->route('songs.index')
            ->with('success', 'Música excluída com sucesso!');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');

        if (empty($search)) {
            return response()->json([]);
        }

        try {
            $songs = Song::with('user:id,name')
                ->where(function($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%')
                          ->orWhere('bible_reference', 'like', '%' . $search . '%')
                          ->orWhere('version', 'like', '%' . $search . '%')
                          ->orWhereHas('user', function($q) use ($search) {
                              $q->where('name', 'like', '%' . $search . '%');
                          });
                })
                ->where('status', 7) // Apenas músicas aprovadas
                ->limit(15)
                ->get(['id', 'title', 'version', 'tone', 'bible_reference', 'id_user']);

            return response()->json($songs);

        } catch (\Exception $e) {
            \Log::error('SongController search - Erro na busca', [
                'search' => $search,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json(['error' => 'Erro na busca'], 500);
        }
    }

    public function allSongs(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $query = Song::where('status', 7);

        // Aplicar filtros se fornecidos
        if ($request->filled('letter')) {
            $letter = $request->letter;
            if ($letter === '#') {
                $query->where('title', 'regexp', '^[0-9]');
            } else {
                $query->where('title', 'like', $letter . '%');
            }
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category)
                  ->where('type', 'category');
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('tone')) {
            $query->where('tone', $request->tone);
        }

        if ($request->filled('intensity')) {
            $query->where('intensity', $request->intensity);
        }

        if ($request->filled('tempo')) {
            $query->where('measure', $request->tempo);
        }

        // Contar total antes de aplicar ordenação e paginação
        $totalSongs = $query->count();

        // Aplicar ordenação
        $sortBy = $request->get('sort', 'title');
        switch ($sortBy) {
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'times':
                $query->orderBy('times', 'desc');
                break;
            case 'times_asc':
                $query->orderBy('times', 'asc');
                break;
            case 'created_at':
                $query->orderBy('created_at', 'desc');
                break;
            case 'created_at_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'title':
            default:
                $query->orderBy('title', 'asc');
                break;
        }

        $songs = $query->offset($offset)->limit($perPage)->get();

        $hasMore = ($offset + $perPage) < $totalSongs;

        if ($request->ajax()) {
            return response()->json([
                'songs' => $songs,
                'hasMore' => $hasMore,
                'currentPage' => $page,
                'totalSongs' => $totalSongs
            ]);
        }

        $categories = Category::all();

        return view('songs.all', compact('songs', 'categories', 'hasMore', 'totalSongs'));
    }

    public function searchSongs(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $query = Song::where('status', 7);

        // Busca por texto (título, versão, referência bíblica)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('version', 'like', '%' . $searchTerm . '%')
                  ->orWhere('bible_reference', 'like', '%' . $searchTerm . '%');
            });
        }

        // Aplicar filtros se fornecidos
        if ($request->filled('letter')) {
            $letter = $request->letter;
            if ($letter === '#') {
                $query->where('title', 'regexp', '^[0-9]');
            } else {
                $query->where('title', 'like', $letter . '%');
            }
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category)
                  ->where('type', 'category');
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('tone')) {
            $query->where('tone', $request->tone);
        }

        if ($request->filled('intensity')) {
            $query->where('intensity', $request->intensity);
        }

        if ($request->filled('tempo')) {
            $query->where('measure', $request->tempo);
        }

        // Contar total antes de aplicar ordenação e paginação
        $totalSongs = $query->count();

        // Aplicar ordenação
        $sortBy = $request->get('sort', 'title');
        switch ($sortBy) {
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'times':
                $query->orderBy('times', 'desc');
                break;
            case 'times_asc':
                $query->orderBy('times', 'asc');
                break;
            case 'created_at':
                $query->orderBy('created_at', 'desc');
                break;
            case 'created_at_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'title':
            default:
                $query->orderBy('title', 'asc');
                break;
        }

        $songs = $query->offset($offset)->limit($perPage)->get();

        $hasMore = ($offset + $perPage) < $totalSongs;

        if ($request->ajax()) {
            return response()->json([
                'songs' => $songs,
                'hasMore' => $hasMore,
                'currentPage' => $page,
                'totalSongs' => $totalSongs
            ]);
        }

        $categories = Category::all();

        return view('songs.search', compact('songs', 'categories', 'hasMore', 'totalSongs'));
    }

    public function searchByBibleReference(Request $request)
    {
        $reference = $request->get('reference');

        $songs = Song::where('bible_reference', 'like', "%{$reference}%")
                     ->where('status', 7)
                     ->get();

        return response()->json($songs);
    }

    public function updateLyrics(Request $request, Song $song)
    {
        $this->authorize('update', $song);

        $validated = $request->validate([
            'lyrics' => 'required|string'
        ]);

        $song->update($validated);
        return redirect()
                ->route('songs.show', $song)
                ->with('success', 'Letra atualizada com sucesso!');
        //return response()->json(['success' => true]);
    }

    public function updateChords(Request $request, Song $song)
    {
        $this->authorize('update', $song);

        $validated = $request->validate([
            'chords' => 'required|string'
        ]);

        $song->update($validated);

        return redirect()
                ->route('songs.show', $song)
                ->with('success', 'Cifra atualizada com sucesso!');
        //return response()->json(['success' => true]);
    }

    public function getLyrics(Song $song)
    {
        return response()->json([
            'title' => $song->title,
            'lyrics' => $song->lyrics
        ]);
    }

    public function getChords(Song $song)
    {
        return response()->json([
            'title'   => $song->title,
            'version' => $song->version,
            'chords'  => $song->chords
        ]);
    }
}
