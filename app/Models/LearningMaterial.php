<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningMaterial extends Model
{
    protected $fillable = ['title', 'learning_material_group_id', 'author', 'published_at'];

    public function contents()
    {
        return $this->hasMany(LearningMaterialContent::class)->orderBy('order')->reorder();;
    }

    public function learningMaterialGroups()
    {
        return $this->belongsTo(LearningMaterialGroup::class, 'learning_material_group_id');
    }
}
