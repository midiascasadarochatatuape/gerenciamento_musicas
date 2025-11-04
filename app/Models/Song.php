<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $fillable = [
        'title',
        'version',
        'image',
        'snippet',
        'tone',
        'tempo',
        'measure',
        'type',
        'intensity',
        'link_youtube',
        'link_spotify',
        'link_drive',
        'lyrics',
        'chords',
        'status',
        'bible_reference',
        'id_user',
        'times',
        'bible_reference'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'schedule_song', 'song_id', 'schedule_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function tutorials()
    {
        return $this->hasMany(SongTutorial::class);
    }
}
