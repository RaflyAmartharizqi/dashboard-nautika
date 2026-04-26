<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningMaterialContent extends Model
{
    protected $fillable = ['learning_material_id', 'type', 'content', 'order'];

    public function learningMaterial()
    {
        return $this->belongsTo(LearningMaterial::class);
    }
}
