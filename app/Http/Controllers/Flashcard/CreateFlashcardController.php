<?php

namespace App\Http\Controllers\Flashcard;

use App\Http\Controllers\Controller;
use App\Models\Flashcard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CreateFlashcardController extends Controller
{
    public function createFlashcard(Request $request) {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'difficulty' => 'required|string|in:easy,medium,hard',
            'is_active' => 'boolean|default:true',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $flashcard = Flashcard::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'category_id' => $request->category_id,
            'difficulty' => $request->difficulty,
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Flashcard created successfully',
            'data' => $request->user(),
        ]);
    }
}
