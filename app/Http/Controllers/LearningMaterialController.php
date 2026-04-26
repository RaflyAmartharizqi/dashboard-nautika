<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LearningMaterial;
use Illuminate\Support\Facades\DB;

class LearningMaterialController extends Controller
{

    public function index()
    {
        $materi = LearningMaterial::latest()->get();

        return response()->json([
            'code' => 200,
            'data' => $materi
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'author' => 'nullable|string',
            'published_at' => 'required|date',
            'contents' => 'required|array',
            'contents.*.type' => 'required|in:text,image',
            'contents.*.content' => 'required'
        ]);

        DB::beginTransaction();

        try {

            $materi = LearningMaterial::create([
                'title' => $request->title,
                'author' => $request->author,
                'published_at' => $request->published_at,
            ]);

            foreach ($request->contents as $index => $item) {

                $contentValue = $item['content'];

                // upload image kalau ada
                if ($item['type'] === 'image' && $request->hasFile("contents.$index.content")) {
                    $contentValue = $request->file("contents.$index.content")
                        ->store('learning_materials', 'public');
                }

                $materi->contents()->create([
                    'type' => $item['type'],
                    'content' => $contentValue,
                    'order' => $index
                ]);
            }

            DB::commit();

            return response()->json([
                'code' => 200,
                'message' => 'Materi berhasil dibuat',
                'data' => $materi->load('contents')
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $materi = LearningMaterial::findOrFail($id);

        $materi->update([
            'title' => $request->title,
            'author' => $request->author,
            'published_at' => $request->published_at,
        ]);

        $materi->contents()->delete();

        foreach ($request->contents as $index => $item) {

            $contentValue = $item['content'];

            if ($item['type'] === 'image' && $request->hasFile("contents.$index.content")) {
                $contentValue = $request->file("contents.$index.content")
                    ->store('learning_materials', 'public');
            }

            $materi->contents()->create([
                'type' => $item['type'],
                'content' => $contentValue,
                'order' => $index
            ]);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Materi berhasil diupdate',
            'data' => $materi->load('contents')
        ]);
    }

    public function show($id)
    {
        $materi = LearningMaterial::with('contents')->findOrFail($id);

        return response()->json([
            'code' => 200,
            'data' => $materi
        ]);
    }

    public function destroy($id)
    {
        try {
            $materi = LearningMaterial::findOrFail($id);

            $materi->delete();

            return response()->json([
                'code' => 200,
                'message' => 'Materi berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
