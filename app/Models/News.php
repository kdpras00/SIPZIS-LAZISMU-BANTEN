<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class News extends Model
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

    protected $appends = [
        'image_url',
        'formatted_date'
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

    
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return 'https://via.placeholder.com/400x250?text=No+Image';
        }

        $image = trim($this->image);

        
        if (filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        }

        
        if (!empty($image)) {
            return Storage::url($image);
        }

        return 'https://via.placeholder.com/400x250?text=No+Image';
    }

    
    public function getImageAttribute($value)
    {
        return $value;
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
