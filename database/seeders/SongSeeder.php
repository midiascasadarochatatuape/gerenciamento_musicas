<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Song;

class SongSeeder extends Seeder
{
    public function run()
    {
        $songs = [
            [
                'title' => 'Oceanos',
                'version' => 'Hillsong',
                'image' => 'default-song.jpg',
                'tempo' => '72',
                'measure' => '4/4',
                'type' => 'atual',
                'intensity' => 'lenta',
                'tone' => 'D',
                'link_youtube' => 'https://www.youtube.com/watch?v=1YBv14CKGwE',
                'times' => 0,
                'status' => 7,
                'id_user' => 1
            ],
            [
                'title' => 'Digno é o Senhor',
                'version' => 'Aline Barros',
                'image' => 'default-song.jpg',
                'tempo' => '75',
                'measure' => '4/4',
                'type' => 'atual',
                'intensity' => 'media',
                'tone' => 'G',
                'link_youtube' => 'https://www.youtube.com/watch?v=FJVeZ3hzqaE',
                'times' => 0,
                'status' => 7,
                'id_user' => 1
            ],
            [
                'title' => 'Grande é o Senhor',
                'version' => 'Adhemar de Campos',
                'image' => 'default-song.jpg',
                'tempo' => '120',
                'measure' => '4/4',
                'type' => 'hino',
                'intensity' => 'rapida',
                'tone' => 'E',
                'link_youtube' => 'https://www.youtube.com/watch?v=XkUZJqR3Uns',
                'times' => 0,
                'status' => 7,
                'id_user' => 1
            ],
            [
                'title' => 'Ao Único',
                'version' => 'Coral Resgate',
                'image' => 'default-song.jpg',
                'tempo' => '68',
                'measure' => '4/4',
                'type' => 'cantico',
                'intensity' => 'lenta',
                'tone' => 'C',
                'link_youtube' => 'https://www.youtube.com/watch?v=PlxKV5J5t8U',
                'times' => 0,
                'status' => 7,
                'id_user' => 1
            ],
            [
                'title' => 'Quão Grande é o Meu Deus',
                'version' => 'Soraya Moraes',
                'image' => 'default-song.jpg',
                'tempo' => '68',
                'measure' => '4/4',
                'type' => 'atual',
                'intensity' => 'media',
                'tone' => 'A',
                'link_youtube' => 'https://www.youtube.com/watch?v=r4J-p8kuxng',
                'times' => 0,
                'status' => 7,
                'id_user' => 1
            ],
            [
                'title' => 'Deus é Fiel',
                'version' => 'Diante do Trono',
                'image' => 'default-song.jpg',
                'tempo' => '85',
                'measure' => '4/4',
                'type' => 'corinho',
                'intensity' => 'media',
                'tone' => 'G',
                'link_youtube' => 'https://www.youtube.com/watch?v=9C_FUZv5YrY',
                'times' => 0,
                'status' => 7,
                'id_user' => 1
            ],
            [
                'title' => 'Em Espírito, Em Verdade',
                'version' => 'Prisma Brasil',
                'image' => 'default-song.jpg',
                'tempo' => '72',
                'measure' => '3/4',
                'type' => 'cantico',
                'intensity' => 'lenta',
                'tone' => 'D',
                'link_youtube' => 'https://www.youtube.com/watch?v=Ky_xTI9Nz_g',
                'times' => 0,
                'status' => 7,
                'id_user' => 1
            ],
            [
                'title' => 'Majestoso Rei',
                'version' => 'Fernandinho',
                'image' => 'default-song.jpg',
                'tempo' => '130',
                'measure' => '4/4',
                'type' => 'atual',
                'intensity' => 'rapida',
                'tone' => 'E',
                'link_youtube' => 'https://www.youtube.com/watch?v=z6L_JXU3U_g',
                'times' => 0,
                'status' => 7,
                'id_user' => 1
            ],
            [
                'title' => 'Rendido Estou',
                'version' => 'Aline Barros',
                'image' => 'default-song.jpg',
                'tempo' => '70',
                'measure' => '4/4',
                'type' => 'atual',
                'intensity' => 'lenta',
                'tone' => 'C',
                'link_youtube' => 'https://www.youtube.com/watch?v=d_YNZVpV3j4',
                'times' => 0,
                'status' => 7,
                'id_user' => 1
            ],
            [
                'title' => 'Aleluia',
                'version' => 'Gabriela Rocha',
                'image' => 'default-song.jpg',
                'tempo' => '72',
                'measure' => '4/4',
                'type' => 'atual',
                'intensity' => 'media',
                'tone' => 'G',
                'link_youtube' => 'https://www.youtube.com/watch?v=PJ3JQhqF9Yk',
                'times' => 0,
                'status' => 7,
                'id_user' => 1
            ],
            [
                'title' => 'Deus do Impossível',
                'version' => 'Coral Resgate',
                'image' => 'default-song.jpg',
                'tempo' => '75',
                'measure' => '4/4',
                'type' => 'corinho',
                'intensity' => 'media',
                'tone' => 'D',
                'link_youtube' => 'https://www.youtube.com/watch?v=y7CXQn2p_ME',
                'times' => 0,
                'status' => 7,
                'id_user' => 1
            ],
            [
                'title' => 'Eu Navegarei',
                'version' => 'Diante do Trono',
                'image' => 'default-song.jpg',
                'tempo' => '128',
                'measure' => '4/4',
                'type' => 'corinho',
                'intensity' => 'rapida',
                'tone' => 'Em',
                'link_youtube' => 'https://www.youtube.com/watch?v=VgC8JV8ZSuY',
                'times' => 0,
                'status' => 7,
                'id_user' => 1
            ],
            [
                'title' => 'Pai Nosso',
                'version' => 'Padre Marcelo Rossi',
                'image' => 'default-song.jpg',
                'tempo' => '70',
                'measure' => '3/4',
                'type' => 'cantico',
                'intensity' => 'lenta',
                'tone' => 'Am',
                'link_youtube' => 'https://www.youtube.com/watch?v=r4OSQYkXwJU',
                'times' => 0,
                'status' => 7,
                'id_user' => 1
            ],
            [
                'title' => 'Vim Para Adorar-Te',
                'version' => 'Diante do Trono',
                'image' => 'default-song.jpg',
                'tempo' => '68',
                'measure' => '4/4',
                'type' => 'atual',
                'intensity' => 'lenta',
                'tone' => 'D',
                'link_youtube' => 'https://www.youtube.com/watch?v=ErJXQZVKVR0',
                'times' => 0,
                'status' => 7,
                'id_user' => 1
            ],
            [
                'title' => 'Santo, Santo, Santo',
                'version' => 'Adoração e Adoradores',
                'image' => 'default-song.jpg',
                'tempo' => '75',
                'measure' => '4/4',
                'type' => 'hino',
                'intensity' => 'media',
                'tone' => 'F',
                'link_youtube' => 'https://www.youtube.com/watch?v=Zk_xr5nK8Eo',
                'times' => 0,
                'status' => 7,
                'id_user' => 1
            ],
            [
                'title' => 'Porque Ele Vive',
                'version' => 'Harpa Cristã',
                'image' => 'default-song.jpg',
                'tempo' => '80',
                'measure' => '3/4',
                'type' => 'hino',
                'intensity' => 'media',
                'tone' => 'C',
                'link_youtube' => 'https://www.youtube.com/watch?v=MqZdZgcyV1o',
                'times' => 0,
                'status' => 7,
                'id_user' => 1
            ],
            [
                'title' => 'Deus é Deus',
                'version' => 'Delino Marçal',
                'image' => 'default-song.jpg',
                'tempo' => '75',
                'measure' => '4/4',
                'type' => 'atual',
                'intensity' => 'media',
                'tone' => 'G',
                'link_youtube' => 'https://www.youtube.com/watch?v=ZQvX3SqKnlY',
                'times' => 0,
                'status' => 7,
                'id_user' => 1
            ],
            [
                'title' => 'Eu Te Louvarei',
                'version' => 'Diante do Trono',
                'image' => 'default-song.jpg',
                'tempo' => '130',
                'measure' => '4/4',
                'type' => 'corinho',
                'intensity' => 'rapida',
                'tone' => 'E',
                'link_youtube' => 'https://www.youtube.com/watch?v=9C_FUZv5YrY',
                'times' => 0,
                'status' => 7,
                'id_user' => 1
            ],
            [
                'title' => 'Alfa e Ômega',
                'version' => 'Marine Friesen',
                'image' => 'default-song.jpg',
                'tempo' => '68',
                'measure' => '4/4',
                'type' => 'atual',
                'intensity' => 'lenta',
                'tone' => 'G',
                'link_youtube' => 'https://www.youtube.com/watch?v=xJJg_xP4y1A',
                'times' => 0,
                'status' => 7,
                'id_user' => 1
            ],
            [
                'title' => 'Teu Santo Nome',
                'version' => 'Gabriela Rocha',
                'image' => 'default-song.jpg',
                'tempo' => '72',
                'measure' => '4/4',
                'type' => 'atual',
                'intensity' => 'media',
                'tone' => 'D',
                'link_youtube' => 'https://www.youtube.com/watch?v=8BQl6C_AUvw',
                'times' => 0,
                'status' => 7,
                'id_user' => 1
            ]
        ];

        foreach ($songs as $song) {
            Song::create($song);
        }
    }
}