<?php

namespace App\Http\Controllers\Flashcard;

use App\Http\Controllers\Controller;
use App\Models\Flashcard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UpdateFlashcardController extends Controller
{
    public function updateFlashcard(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'difficulty' => 'required|in:easy,medium,hard',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $flashcard = Flashcard::find($id);

        if (!$flashcard) {
            return response()->json([
                'status' => 'error',
                'message' => 'Flashcard not found',
            ], 404);
        }

        $flashcard->question = $request->question;
        $flashcard->answer = $request->answer;
        $flashcard->category_id = $request->category_id;
        $flashcard->difficulty = $request->difficulty;
        $flashcard->is_active = $request->is_active;
        $flashcard->save();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Flashcard updated successfully',
            'data' => $flashcard,
        ]);
    }
}
