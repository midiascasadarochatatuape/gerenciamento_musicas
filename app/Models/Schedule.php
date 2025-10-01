<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'group_id',
        'date',
        'time',
        'event_type',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function group_user()
    {
        return $this->belongsToMany(GroupUser::class);
    }

    public function songs()
    {
        return $this->belongsToMany(Song::class, 'schedule_song', 'schedule_id', 'song_id')
                    ->withPivot('order', 'tone', 'notes')
                    ->orderBy('schedule_song.order', 'asc');
    }

    public function getFormattedTimeAttribute()
    {
        if (!$this->time) {
            return '-';
        }

        $hour = $this->time->format('H');
        $minute = $this->time->format('i');

        return $minute === '00' ? "{$hour}h" : "{$hour}h{$minute}";
    }

}
