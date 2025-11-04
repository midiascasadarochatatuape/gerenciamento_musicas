<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SongTutorial extends Model
{
    protected $fillable = [
        'song_id',
        'instrument',
        'title',
        'url'
    ];

    // Instrumentos disponíveis
    public static function getInstruments()
    {
        return ['Guitarra', 'Teclado', 'Violão', 'Bateria', 'Baixo', 'Sopro', 'Cordas'];
    }

    // Relacionamento com Song
    public function song()
    {
        return $this->belongsTo(Song::class);
    }
}
