<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningMaterial extends Model
{
    protected $fillable = ['title', 'author', 'published_at'];

    public function contents()
    {
        return $this->hasMany(LearningMaterialContent::class)->orderBy('order')->reorder();;
    }
}
