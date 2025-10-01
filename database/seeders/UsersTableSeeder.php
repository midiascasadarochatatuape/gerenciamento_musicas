<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@servitium.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'type_user' => 'admin',
            'instrument_1' => 'Vocal',
        ]);

        // Técnico
        User::create([
            'name' => 'Technical Manager',
            'email' => 'tech@servitium.com',
            'username' => 'tecnico',
            'password' => Hash::make('password'),
            'type_user' => 'tecnico',
            'instrument_1' => 'Sound Tech',
        ]);

        // Músicos
        $musicians = [
            [
                'name' => 'João Silva',
                'instrument_1' => 'Guitar',
                'instrument_2' => 'Vocal'
            ],
            [
                'name' => 'Maria Santos',
                'instrument_1' => 'Piano',
                'instrument_2' => 'Keyboard'
            ],
            [
                'name' => 'Pedro Oliveira',
                'instrument_1' => 'Bass',
            ],
            [
                'name' => 'Ana Costa',
                'instrument_1' => 'Drums',
            ],
            [
                'name' => 'Lucas Pereira',
                'instrument_1' => 'Violin',
                'instrument_2' => 'Guitar'
            ],
            [
                'name' => 'Carla Souza',
                'instrument_1' => 'Vocal',
            ],
            [
                'name' => 'Rafael Santos',
                'instrument_1' => 'Saxophone',
            ],
            [
                'name' => 'Julia Lima',
                'instrument_1' => 'Vocal',
                'instrument_2' => 'Piano'
            ],
        ];

        foreach ($musicians as $index => $musician) {
            User::create([
                'name' => $musician['name'],
                'email' => strtolower(str_replace(' ', '.', $musician['name'])) . '@servitium.com',
                'username' => strtolower(explode(' ', $musician['name'])[0]),
                'password' => Hash::make('password'),
                'type_user' => 'musico',
                'instrument_1' => $musician['instrument_1'],
                'instrument_2' => $musician['instrument_2'] ?? null,
            ]);
        }
    }
}