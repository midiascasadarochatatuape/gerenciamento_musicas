<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Group;
use App\Models\Song;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Pega IDs de todos os grupos que o usuário participa
        $groupIds = $user->groups()->pluck('groups.id');

        // Busca os próximos agendamentos desses grupos
        $schedules = Schedule::with(['group', 'songs'])
            ->orderBy('date', 'asc')
            ->paginate(8);

       //dd($groupIds);
        return view('schedules.index', compact('schedules', 'groupIds'));
    }

    public function create()
    {
        $groups = Group::orderBy('name', 'asc')->get();
        $songs = Song::where('status', 7)->get(); // Only approved songs
        return view('schedules.create', compact('groups', 'songs'));
    }

    public function store(Request $request)
    {
        \Log::info('ScheduleController store - Início', [
            'request_all' => $request->all(),
            'method' => $request->method()
        ]);

        // Filtrar arrays vazios antes da validação
        $songsArray = $request->input('songs', []);
        $filteredSongs = array_filter($songsArray, function($song) {
            return !empty($song);
        });

        // Substituir no request
        $request->merge(['songs' => empty($filteredSongs) ? null : array_values($filteredSongs)]);

        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'date' => 'required|date',
            'time' => 'nullable|date_format:H:i',
            'event_type' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'songs' => 'nullable|array',
            'songs.*' => 'nullable|exists:songs,id',
            'tones.*' => 'nullable|string|max:10',
            'song_notes.*' => 'nullable|string'
        ]);

        \Log::info('ScheduleController store - Validação passou', ['validated' => $validated]);

        $schedule = Schedule::create([
            'group_id' => $validated['group_id'],
            'date' => $validated['date'],
            'time' => $validated['time'],
            'event_type' => $validated['event_type'],
            'notes' => $validated['notes']
        ]);

        // Só processa músicas se foram selecionadas
        if (!empty($validated['songs'])) {
            $songData = [];
            foreach ($validated['songs'] as $index => $songId) {
                // Filtrar valores vazios
                if (!empty($songId)) {
                    $songData[$songId] = [
                        'order' => $index + 1,
                        'tone' => $request->tones[$index] ?? null,
                        'notes' => $request->song_notes[$index] ?? null
                    ];

                    // Increment times counter for each song
                    Song::where('id', $songId)->increment('times');
                }
            }

            if (!empty($songData)) {
                $schedule->songs()->attach($songData);
            }
        }

        \Log::info('ScheduleController store - Escala criada', ['schedule_id' => $schedule->id]);

        return redirect()->route('schedules.index')
            ->with('success', 'Escala criada com sucesso!');
    }

    public function update(Request $request, Schedule $schedule)
    {
        \Log::info('ScheduleController update - Início', [
            'schedule_id' => $schedule->id,
            'request_all' => $request->all(),
        ]);

        // Filtrar arrays vazios antes da validação
        $songsArray = $request->input('songs', []);
        $filteredSongs = array_filter($songsArray, function($song) {
            return !empty($song);
        });

        // Substituir no request - permitir array vazio para remoção de todas as músicas
        $request->merge(['songs' => array_values($filteredSongs)]);

        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'date' => 'required|date',
            'time' => 'nullable|date_format:H:i',
            'event_type' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'songs' => 'nullable|array',  // Permitir array vazio
            'songs.*' => 'exists:songs,id',
            'tones.*' => 'nullable|string|max:10',
            'song_notes.*' => 'nullable|string'
        ]);

        // Get current songs before any changes
        $oldSongIds = $schedule->songs->pluck('id');
        $newSongIds = collect($validated['songs'] ?? []);

        \Log::info('ScheduleController update - Comparação de músicas', [
            'old_songs' => $oldSongIds->toArray(),
            'new_songs' => $newSongIds->toArray()
        ]);

        // Find songs that were removed
        $removedSongIds = $oldSongIds->diff($newSongIds);

        // Decrement times for removed songs
        if ($removedSongIds->isNotEmpty()) {
            \Log::info('ScheduleController update - Decrementando times', ['removed_songs' => $removedSongIds->toArray()]);
            Song::whereIn('id', $removedSongIds)->decrement('times');
        }

        // Find and increment new songs
        $addedSongIds = $newSongIds->diff($oldSongIds);
        if ($addedSongIds->isNotEmpty()) {
            \Log::info('ScheduleController update - Incrementando times', ['added_songs' => $addedSongIds->toArray()]);
            Song::whereIn('id', $addedSongIds)->increment('times');
        }

        // Update schedule
        $schedule->update([
            'group_id' => $validated['group_id'],
            'date' => $validated['date'],
            'time' => $validated['time'],
            'event_type' => $validated['event_type'],
            'notes' => $validated['notes']
        ]);

        // Update song relationships - permitir array vazio
        $songData = [];
        if (!empty($validated['songs'])) {
            foreach ($validated['songs'] as $index => $songId) {
                if (!empty($songId)) {
                    $songData[$songId] = [
                        'order' => $index + 1,
                        'tone' => $request->tones[$index] ?? null,
                        'notes' => $request->song_notes[$index] ?? null
                    ];
                }
            }
        }

        $schedule->songs()->sync($songData);

        \Log::info('ScheduleController update - Escala atualizada', ['schedule_id' => $schedule->id]);

        return redirect()->route('schedules.index')
            ->with('success', 'Escala atualizada com sucesso!');
    }

    public function destroy(Schedule $schedule)
    {
        // Decrement times counter for all songs in this schedule
        $schedule->songs->each(function ($song) {
            $song->decrement('times');
        });

        $schedule->delete();
        return redirect()->route('schedules.index')
            ->with('success', 'Escala excluída com sucesso!');
    }

    public function edit(Schedule $schedule)
        {
            $schedule->load(['group', 'songs']);
            $groups = Group::orderBy('name', 'asc')->get();
            $songs = Song::where('status', 7)->get();
            return view('schedules.edit', compact('schedule', 'groups', 'songs'));
        }

    public function userNextSchedule()
    {
        $nextSchedules = Schedule::whereHas('group.users', function($query) {
                $query->where('users.id', auth()->id());
            })
            ->where('date', '>=', now())
            ->orderBy('date', 'asc')
            ->get(); // <-- agora retorna todos os resultados futuros

        return view('schedules.user-schedules', compact('nextSchedules'));
    }


    public function setlist(Schedule $schedule)
    {
        return view('schedules.setlist', compact('schedule'));
    }

    public function updateSongsOrder(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'songs' => 'required|array',
            'songs.*' => 'exists:songs,id',
        ]);

        $songData = [];
        foreach ($validated['songs'] as $index => $songId) {
            // Obter os dados existentes da relação
            $pivotData = $schedule->songs()->where('song_id', $songId)->first();

            if ($pivotData) {
                // Se a música já existe na escala, mantém os dados existentes
                $songData[$songId] = [
                    'order' => $index + 1,
                    'tone' => $pivotData->pivot->tone,
                    'notes' => $pivotData->pivot->notes
                ];
            } else {
                // Se é uma música nova, adiciona com valores padrão
                $songData[$songId] = [
                    'order' => $index + 1,
                    'tone' => null,
                    'notes' => null
                ];

                // Incrementa o contador de vezes para a nova música
                Song::where('id', $songId)->increment('times');
            }
        }

        try {
            $schedule->songs()->sync($songData);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar a ordem: ' . $e->getMessage()
            ], 500);
        }
    }
}
