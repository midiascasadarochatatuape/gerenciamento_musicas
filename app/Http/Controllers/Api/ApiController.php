<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Group;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class ApiController extends Controller
{
    /**
     * Retorna todas as escalas com suas músicas e grupos
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getSchedules(Request $request): JsonResponse
    {
        try {
            \Log::info('API getSchedules - Início', ['params' => $request->all()]);

            $query = Schedule::with([
                'group:id,name',
                'songs' => function($q) {
                    $q->select('songs.id', 'songs.title', 'songs.version', 'songs.bible_reference', 'songs.type')
                      ->orderBy('schedule_song.order');
                }
            ]);

            // Filtro por grupo se especificado
            if ($request->has('group_id')) {
                $query->where('group_id', $request->group_id);
            }

            // Filtro por data inicial
            if ($request->has('date_from')) {
                $query->where('date', '>=', $request->date_from);
            }

            // Filtro por data final
            if ($request->has('date_to')) {
                $query->where('date', '<=', $request->date_to);
            }

            // Filtro para escalas futuras
            if ($request->has('upcoming') && $request->upcoming == 'true') {
                $query->where('date', '>=', Carbon::today());
            }

            $schedules = $query->orderBy('date', 'desc')->get();

            \Log::info('API getSchedules - Escalas encontradas', ['count' => $schedules->count()]);

            $formattedSchedules = $schedules->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'date' => $schedule->date->format('Y-m-d'),
                    'time' => $schedule->time ? $schedule->time->format('H:i') : null,
                    'event_type' => $schedule->event_type,
                    'notes' => $schedule->notes,
                    'group' => $schedule->group ? [
                        'id' => $schedule->group->id,
                        'name' => $schedule->group->name
                    ] : null,
                    'songs' => $schedule->songs ? $schedule->songs->map(function ($song) {
                        return [
                            'id' => $song->id,
                            'title' => $song->title,
                            'version' => $song->version,
                            'type' => $song->type,
                            'bible_reference' => $song->bible_reference,
                            'order' => $song->pivot->order ?? null,
                            'tone' => $song->pivot->tone ?? null,
                            'notes' => $song->pivot->notes ?? null
                        ];
                    })->values() : []
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedSchedules,
                'total' => $schedules->count(),
                'generated_at' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            \Log::error('API getSchedules - Erro', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar escalas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna uma escala específica com detalhes completos
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getSchedule($id): JsonResponse
    {
        try {
            $schedule = Schedule::with([
                'group:id,name',
                'songs' => function($q) {
                    $q->select('songs.id', 'songs.title', 'songs.version', 'songs.type', 'songs.bible_reference', 'songs.lyrics', 'songs.chords')
                      ->orderBy('schedule_song.order');
                }
            ])->find($id);

            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Escala não encontrada'
                ], 404);
            }

            $formattedSchedule = [
                'id' => $schedule->id,
                'date' => $schedule->date->format('Y-m-d'),
                'time' => $schedule->time ? $schedule->time->format('H:i') : null,
                'event_type' => $schedule->event_type,
                'notes' => $schedule->notes,
                'group' => [
                    'id' => $schedule->group->id,
                    'name' => $schedule->group->name
                ],
                'songs' => $schedule->songs->map(function ($song) {
                    return [
                        'id' => $song->id,
                        'title' => $song->title,
                        'version' => $song->version,
                        'type' => $song->type,
                        'bible_reference' => $song->bible_reference,
                        'lyrics' => $song->lyrics,
                        'chords' => $song->chords,
                        'order' => $song->pivot->order,
                        'tone' => $song->pivot->tone,
                        'notes' => $song->pivot->notes
                    ];
                })->values()
            ];

            return response()->json([
                'success' => true,
                'data' => $formattedSchedule,
                'generated_at' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar escala',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna todos os grupos com suas informações básicas
     *
     * @return JsonResponse
     */
    public function getGroups(): JsonResponse
    {
        try {
            $groups = Group::with('users:id,name,email,type_user')->get();

            $formattedGroups = $groups->map(function ($group) {
                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'members_count' => $group->users->count(),
                    'members' => $group->users->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'type' => $user->type_user,
                            'position' => $user->pivot->position ?? null
                        ];
                    })->values()
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedGroups,
                'total' => $groups->count(),
                'generated_at' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar grupos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna informações de um grupo específico
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getGroup($id): JsonResponse
    {
        try {
            $group = Group::with([
                'users:id,name,email,type_user',
                'schedules' => function($q) {
                    $q->select('id', 'group_id', 'date', 'time', 'event_type')
                      ->orderBy('date', 'desc')
                      ->limit(10);
                }
            ])->find($id);

            if (!$group) {
                return response()->json([
                    'success' => false,
                    'message' => 'Grupo não encontrado'
                ], 404);
            }

            $formattedGroup = [
                'id' => $group->id,
                'name' => $group->name,
                'members_count' => $group->users->count(),
                'members' => $group->users->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'type' => $user->type_user,
                        'position' => $user->pivot->position ?? null
                    ];
                })->values(),
                'recent_schedules' => $group->schedules->map(function ($schedule) {
                    return [
                        'id' => $schedule->id,
                        'date' => $schedule->date->format('Y-m-d'),
                        'time' => $schedule->time ? $schedule->time->format('H:i') : null,
                        'event_type' => $schedule->event_type
                    ];
                })->values()
            ];

            return response()->json([
                'success' => true,
                'data' => $formattedGroup,
                'generated_at' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar grupo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna todas as músicas aprovadas
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getSongs(Request $request): JsonResponse
    {
        try {
            $query = Song::where('status', 7); // Apenas músicas aprovadas

            // Filtro por busca de texto
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('version', 'like', "%{$search}%")
                      ->orWhere('bible_reference', 'like', "%{$search}%");
                });
            }

            // Filtro por versão
            if ($request->has('version')) {
                $query->where('version', 'like', "%{$request->version}%");
            }

            // Filtro por tipo
            if ($request->has('type')) {
                $query->where('type', $request->type);
            }

            $songs = $query->orderBy('title')->get();

            $formattedSongs = $songs->map(function ($song) {
                return [
                    'id' => $song->id,
                    'title' => $song->title,
                    'version' => $song->version,
                    'type' => $song->type,
                    'bible_reference' => $song->bible_reference,
                    'lyrics' => $song->lyrics,
                    'chords' => $song->chords,
                    'times_played' => $song->times ?? 0,
                    'created_at' => $song->created_at->format('Y-m-d H:i:s')
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedSongs,
                'total' => $songs->count(),
                'generated_at' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar músicas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna uma música específica
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getSong($id): JsonResponse
    {
        try {
            $song = Song::where('status', 7)->find($id);

            if (!$song) {
                return response()->json([
                    'success' => false,
                    'message' => 'Música não encontrada ou não aprovada'
                ], 404);
            }

            $formattedSong = [
                'id' => $song->id,
                'title' => $song->title,
                'version' => $song->version,
                'type' => $song->type,
                'bible_reference' => $song->bible_reference,
                'lyrics' => $song->lyrics,
                'chords' => $song->chords,
                'times_played' => $song->times ?? 0,
                'created_at' => $song->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $song->updated_at->format('Y-m-d H:i:s')
            ];

            return response()->json([
                'success' => true,
                'data' => $formattedSong,
                'generated_at' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar música',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Endpoint de informações da API
     *
     * @return JsonResponse
     */
    public function info(): JsonResponse
    {
        return response()->json([
            'api_name' => 'Louvor Management API',
            'version' => '1.0.0',
            'description' => 'API para gerenciamento de escalas e músicas de louvor',
            'endpoints' => [
                'GET /api/schedules' => 'Lista todas as escalas com filtros opcionais',
                'GET /api/schedules/{id}' => 'Retorna uma escala específica',
                'GET /api/groups' => 'Lista todos os grupos',
                'GET /api/groups/{id}' => 'Retorna um grupo específico',
                'GET /api/songs' => 'Lista todas as músicas aprovadas',
                'GET /api/songs/{id}' => 'Retorna uma música específica',
                'GET /api/info' => 'Informações da API'
            ],
            'parameters' => [
                'schedules' => [
                    'group_id' => 'Filtrar por ID do grupo',
                    'date_from' => 'Data inicial (Y-m-d)',
                    'date_to' => 'Data final (Y-m-d)',
                    'upcoming' => 'true para escalas futuras apenas'
                ],
                'songs' => [
                    'search' => 'Busca por título, versão ou referência bíblica',
                    'version' => 'Filtrar por versão/intérprete',
                    'type' => 'Filtrar por tipo (hino, corinho, cantico, atual)'
                ]
            ],
            'generated_at' => now()->toISOString()
        ]);
    }
}
