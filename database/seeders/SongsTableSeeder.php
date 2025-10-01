<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Song;
use App\Models\User;

class SongsTableSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('type_user', 'admin')->first();
        $tecnico = User::where('type_user', 'tecnico')->first();

        $songs = [
            [
                'title' => 'Amazing Grace',
                'tone' => 'G',
                'tempo' => '70',
                'measure' => '4/4',
                'times' => rand(0, 30),
                'id_user' => $admin->id,
                'status' => 7,
            ],
            [
                'title' => 'How Great Is Our God',
                'tone' => 'A',
                'tempo' => '75',
                'measure' => '4/4',
                'times' => rand(0, 30),
                'id_user' => $tecnico->id,
                'status' => 7,
            ],
            [
                'title' => 'Oceans',
                'tone' => 'D',
                'tempo' => '132',
                'measure' => '4/4',
                'times' => rand(0, 30),
                'id_user' => $admin->id,
                'status' => 7,
            ],
            [
                'title' => '10,000 Reasons',
                'tone' => 'G',
                'tempo' => '73',
                'measure' => '4/4',
                'times' => rand(0, 30),
                'id_user' => $tecnico->id,
                'status' => 7,
            ],
            [
                'title' => 'Here I Am to Worship',
                'tone' => 'E',
                'tempo' => '65',
                'measure' => '4/4',
                'times' => rand(0, 30),
                'id_user' => $admin->id,
                'status' => 7,
            ],
            [
                'title' => 'Mighty to Save',
                'tone' => 'A',
                'tempo' => '74',
                'measure' => '4/4',
                'times' => rand(0, 30),
                'id_user' => $tecnico->id,
                'status' => 7,
            ],
            [
                'title' => 'Blessed Be Your Name',
                'tone' => 'A',
                'tempo' => '140',
                'measure' => '4/4',
                'times' => rand(0, 30),
                'id_user' => $admin->id,
                'status' => 7,
            ],
            [
                'title' => 'Shout to the Lord',
                'tone' => 'A',
                'tempo' => '72',
                'measure' => '4/4',
                'times' => rand(0, 30),
                'id_user' => $tecnico->id,
                'status' => 7,
            ],
            [
                'title' => 'Forever',
                'tone' => 'G',
                'tempo' => '120',
                'measure' => '4/4',
                'times' => rand(0, 30),
                'id_user' => $admin->id,
                'status' => 7,
            ],
            [
                'title' => 'Open the Eyes of My Heart',
                'tone' => 'E',
                'tempo' => '65',
                'measure' => '4/4',
                'times' => rand(0, 30),
                'id_user' => $tecnico->id,
                'status' => 7,
            ],
            [
                'title' => 'Your Grace Is Enough',
                'tone' => 'G',
                'tempo' => '120',
                'measure' => '4/4',
                'times' => rand(0, 30),
                'id_user' => $admin->id,
                'status' => 7,
            ],
            [
                'title' => 'Lord I Need You',
                'tone' => 'A',
                'tempo' => '72',
                'measure' => '4/4',
                'times' => rand(0, 30),
                'id_user' => $tecnico->id,
                'status' => 7,
            ],
            [
                'title' => 'Great Are You Lord',
                'tone' => 'D',
                'tempo' => '72',
                'measure' => '4/4',
                'times' => rand(0, 30),
                'id_user' => $admin->id,
                'status' => 7,
            ],
            [
                'title' => 'Good Good Father',
                'tone' => 'A',
                'tempo' => '70',
                'measure' => '4/4',
                'times' => rand(0, 30),
                'id_user' => $tecnico->id,
                'status' => 7,
            ],
            [
                'title' => 'This Is Amazing Grace',
                'tone' => 'G',
                'tempo' => '100',
                'measure' => '4/4',
                'times' => rand(0, 30),
                'id_user' => $admin->id,
                'status' => 7,
            ],
            [
                'title' => 'King of My Heart',
                'tone' => 'C',
                'tempo' => '68',
                'measure' => '4/4',
                'times' => rand(0, 30),
                'id_user' => $tecnico->id,
                'status' => 7,
            ],
            [
                'title' => 'Build My Life',
                'tone' => 'D',
                'tempo' => '70',
                'measure' => '4/4',
                'times' => rand(0, 30),
                'id_user' => $admin->id,
                'status' => 7,
            ],
            [
                'title' => 'What a Beautiful Name',
                'tone' => 'D',
                'tempo' => '68',
                'measure' => '4/4',
                'times' => rand(0, 30),
                'id_user' => $tecnico->id,
                'status' => 7,
            ],
            [
                'title' => 'Reckless Love',
                'tone' => 'D',
                'tempo' => '85',
                'measure' => '4/4',
                'times' => rand(0, 30),
                'id_user' => $admin->id,
                'status' => 7,
            ],
            [
                'title' => 'Living Hope',
                'tone' => 'C',
                'tempo' => '72',
                'measure' => '4/4',
                'times' => rand(0, 30),
                'id_user' => $tecnico->id,
                'status' => 7,
            ],
        ];

        foreach ($songs as $song) {
            Song::create($song);
        }
    }
}
