<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'type_user' => 'required|in:admin,tecnico,musico',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('user.index')->with('success', 'Usuário criado com sucesso');
    }

    public function edit(User $user)
    {
        $this->authorize('edit', $user);

        // Recupera os grupos com suas posições
        $positions = DB::table('group_user')
            ->where('user_id', $user->id)
            ->pluck('group_id', 'position');

        // Define dinamicamente os valores no objeto $user
        $user->group_1 = $positions['group_1'] ?? null;
        $user->group_2 = $positions['group_2'] ?? null;

        $groups = Group::all();

        return view('users.edit', compact('user', 'groups'));
    }


    public function update(Request $request, User $user)
    {
        //$this->authorize('update', $user);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'group_1' => ['nullable', 'exists:groups,id', 'different:group_2'],
            'group_2' => ['nullable', 'exists:groups,id', 'different:group_1'],
            'instrument_1' => ['nullable', 'string', 'max:255'],
            'instrument_2' => ['nullable', 'string', 'max:255'],
        ];

        if (auth()->user()->type_user === 'admin') {
            $rules['type_user'] = ['required', 'in:admin,musico,tecnico'];
        }

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:6'];
        }

        $validator = Validator::make($request->all(), $rules);

        $validator->after(function ($validator) use ($request) {
            if (
                $request->filled('instrument_1') &&
                $request->filled('instrument_2') &&
                $request->instrument_1 === $request->instrument_2
            ) {
                $validator->errors()->add('instrument_2', 'Os instrumentos não podem ser iguais.');
            }
        });

        $validated = $validator->validate();

        // Prepare data for update
        $updateData = Arr::except($validated, ['group_1', 'group_2']);

        // Handle password update
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        } else {
            // Remove password from update data if not provided
            unset($updateData['password']);
        }

        // Atualiza os vínculos na tabela group_user
        $user->update($updateData);
        // Remove todos os registros anteriores do usuário
        DB::table('group_user')->where('user_id', $user->id)->delete();

        $insert = [];

        if (!empty($validated['group_1'])) {
            $insert[] = [
                'user_id' => $user->id,
                'group_id' => $validated['group_1'],
                'position' => 'group_1',
            ];
        }

        if (!empty($validated['group_2'])) {
            $insert[] = [
                'user_id' => $user->id,
                'group_id' => $validated['group_2'],
                'position' => 'group_2',
            ];
        }

        if (!empty($insert)) {
            DB::table('group_user')->insert($insert);
        }

        return redirect()->route('user.edit', $user)->with('success', 'Dados atualizados com sucesso!');
    }

}
