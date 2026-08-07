<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
        'name',
        'type',
        'parent_id'
    ];

    public function parent()
    {
        return $this->belongsTo(Region::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Region::class, 'parent_id');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByParent($query, $parentId)
    {
        return $query->where('parent_id', $parentId);
    }
}
