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
        $users = User::all();
        return view('groups.create', compact('users'));
    }



    public function store(Request $request)
{
    // Limpa valores nulos do array de usuários
    $users = array_filter($request->users, function ($value) {
        return !is_null($value) && $value !== '';
    });

    $request->merge(['users' => $users]);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'users' => 'required|array|min:1',
        'users.*' => 'exists:users,id',
    ]);

    $group = Group::create([
        'name' => $validated['name'],
    ]);

    $insert = [];

    foreach ($validated['users'] as $userId) {
        // Verifica quantas posições o usuário já tem
        $existingPositions = DB::table('group_user')
            ->where('user_id', $userId)
            ->pluck('position')
            ->toArray();

        if (in_array('group_1', $existingPositions) && in_array('group_2', $existingPositions)) {
            $user = \App\Models\User::find($userId);
            return redirect()->back()->withErrors([
                'users' => "O usuário {$user->name} já está em dois grupos.",
            ])->withInput();
        }

        $position = in_array('group_1', $existingPositions) ? 'group_2' : 'group_1';

        $insert[] = [
            'user_id' => $userId,
            'group_id' => $group->id,
            'position' => $position,
        ];
    }

    // Insere os vínculos com posições
    DB::table('group_user')->insert($insert);

    return redirect()->route('group.index')
        ->with('success', 'Grupo criado com sucesso!');
}




    public function edit(Group $group)
    {
        $users = User::all();
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
