<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Importa a classe de notificação
use App\Notifications\MyPasswordResetNotification;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'group_1',
        'group_2',
        'instrument_1',
        'instrument_2',
        'type_user',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function primaryGroup()
    {
        return $this->belongsTo(Group::class, 'group_1');
    }

    public function secondaryGroup()
    {
        return $this->belongsTo(Group::class, 'group_2');
    }

    public function songs()
    {
        return $this->hasMany(Song::class, 'id_user');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }


    /**
     * Envie a notificação de reset de senha.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MyPasswordResetNotification($token));
    }
}
