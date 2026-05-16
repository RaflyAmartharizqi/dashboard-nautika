<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningMaterialGroup extends Model
{
    
    protected $fillable = ['name'];

    public function learningMaterials()
    {
        return $this->hasMany(LearningMaterial::class);
    }
}
