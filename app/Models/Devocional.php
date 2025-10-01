<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Devocional extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'bible_references',
        'devotional_date',
        'is_published',
        'views',
        'user_id'
    ];

    protected $casts = [
        'bible_references' => 'array',
        'devotional_date' => 'date',
        'is_published' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($devocional) {
            if (empty($devocional->slug)) {
                $devocional->slug = Str::slug($devocional->title);
            }
        });

        static::updating(function ($devocional) {
            if ($devocional->isDirty('title')) {
                $devocional->slug = Str::slug($devocional->title);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('devotional_date', 'desc')->limit($limit);
    }

    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }

        return Str::limit(strip_tags($this->content), 150);
    }

    public function incrementViews()
    {
        $this->increment('views');
    }
}
