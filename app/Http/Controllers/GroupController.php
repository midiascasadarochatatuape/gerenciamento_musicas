<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::with('users')->paginate(10);
        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        $users = User::orderBy('name', 'asc')->get();
        return view('groups.create', compact('users'));
    }



    public function store(Request $request)
{
        try {
            // Debug - Log completo dos dados recebidos
            \Log::info('GroupController store - Dados completos:', [
                'request_all' => $request->all(),
                'users_raw' => $request->users,
                'name' => $request->name,
                'method' => $request->method(),
                'has_users' => $request->has('users')
            ]);

            // Validação inicial do nome
            $request->validate([
                'name' => 'required|string|max:255'
            ]);

            // Verificar e processar usuários
            $usersInput = $request->input('users', []);
            $users = array_filter($usersInput, function ($value) {
                return !is_null($value) && $value !== '' && $value !== '0';
            });

            \Log::info('Usuários processados:', [
                'input_original' => $usersInput,
                'apos_filtro' => $users,
                'count' => count($users)
            ]);

            // Verificar se há usuários
            if (empty($users)) {
                \Log::warning('Nenhum usuário selecionado');
                return redirect()->back()
                    ->withErrors(['users' => 'Selecione pelo menos um usuário para o grupo.'])
                    ->withInput();
            }

            // Validar usuários
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Validar se os usuários existem
            foreach ($users as $userId) {
                if (!User::find($userId)) {
                    \Log::error('Usuário não encontrado:', ['user_id' => $userId]);
                    return redirect()->back()
                        ->withErrors(['users' => 'Um dos usuários selecionados não é válido.'])
                        ->withInput();
                }
            }

            // Criar o grupo
            $group = Group::create([
                'name' => $validated['name'],
            ]);

            \Log::info('Grupo criado:', ['group_id' => $group->id, 'name' => $group->name]);

            // Preparar dados para inserção
            $insert = [];
            foreach ($users as $userId) {
                // Verificar posições existentes do usuário
                $existingPositions = DB::table('group_user')
                    ->where('user_id', $userId)
                    ->pluck('position')
                    ->toArray();

                \Log::info('Posições existentes do usuário:', ['user_id' => $userId, 'positions' => $existingPositions]);

                // Verificar se já tem duas posições
                if (in_array('group_1', $existingPositions) && in_array('group_2', $existingPositions)) {
                    $user = User::find($userId);
                    \Log::warning('Usuário já está em dois grupos:', ['user_id' => $userId, 'user_name' => $user->name]);

                    // Deletar o grupo criado se houve erro
                    $group->delete();

                    return redirect()->back()->withErrors([
                        'users' => "O usuário {$user->name} já está em dois grupos.",
                    ])->withInput();
                }

                $position = in_array('group_1', $existingPositions) ? 'group_2' : 'group_1';

                $insert[] = [
                    'user_id' => $userId,
                    'group_id' => $group->id,
                    'position' => $position,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            \Log::info('Dados para inserção na group_user:', $insert);

            // Inserir relacionamentos
            if (!empty($insert)) {
                DB::table('group_user')->insert($insert);
                \Log::info('Relacionamentos inseridos com sucesso');
            }

            // Verificar se os dados foram inseridos
            $insertedCount = DB::table('group_user')->where('group_id', $group->id)->count();
            \Log::info('Verificação pós-inserção:', [
                'group_id' => $group->id,
                'users_inserted' => $insertedCount,
                'expected' => count($users)
            ]);

            return redirect()->route('group.index')
                ->with('success', 'Grupo criado com sucesso!');

        } catch (\Exception $e) {
            \Log::error('Erro ao criar grupo:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Erro ao criar grupo: ' . $e->getMessage()])
                ->withInput();
        }
    }




    public function edit(Group $group)
    {
        $users = User::orderBy('name', 'asc')->get();
        $selectedUsers = $group->users()->orderBy('group_user.id')->get();
        return view('groups.edit', compact('group', 'users', 'selectedUsers'));
    }

    public function update(Request $request, Group $group)
    {
        $users = array_filter($request->users, function($value) {
            return !is_null($value) && $value !== '';
        });

        $request->merge(['users' => $users]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'users' => 'required|array|min:1',
            'users.*' => 'exists:users,id'
        ]);

        $group->update([
            'name' => $validated['name']
        ]);

        $syncData = [];

        foreach ($validated['users'] as $userId) {
            // Conta quantos grupos o usuário está, excluindo o atual
            $existingGroupsCount = DB::table('group_user')
                ->where('user_id', $userId)
                ->where('group_id', '!=', $group->id)
                ->count();

            $position = $existingGroupsCount === 0 ? 'group_1' : 'group_2';

            $syncData[$userId] = ['position' => $position];
        }

        $group->users()->sync($syncData);

        return redirect()->route('group.index')
            ->with('success', 'Grupo atualizado com sucesso!');
    }


    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('group.index')->with('success', 'Group deleted successfully');
    }
}
