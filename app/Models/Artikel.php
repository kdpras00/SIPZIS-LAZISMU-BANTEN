<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Artikel extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'image',
        'slug',
        'is_published',
        'excerpt',
        'author_id'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    
    public function getRouteKeyName()
    {
        return 'id';
    }

    
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    
    public static function generateSlug($title)
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'LIKE', "$slug%")->count();
        
        return $count ? "{$slug}-{$count}" : $slug;
    }

    
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y');
    }

    
    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        return Str::limit(strip_tags($this->content), 150);
    }
}