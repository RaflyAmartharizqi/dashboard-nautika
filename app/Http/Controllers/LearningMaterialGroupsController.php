<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LearningMaterialGroup;
use Illuminate\Support\Facades\DB;

class LearningMaterialGroupsController extends Controller
{
        public function index()
        {
            $groups = LearningMaterialGroup::oldest()->get();
    
            return response()->json([
                'code' => 200,
                'data' => $groups
            ]);
        }
}
