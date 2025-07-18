<?php

namespace App\Http\Controllers\Flashcard;

use App\Http\Controllers\Controller;
use App\Models\Flashcard;
use Illuminate\Http\Request;

class DeleteFlashcardController extends Controller
{
    public function deleteFlashcard(Request $request, $id) {
        $flashcard = Flashcard::find($id);
        $flashcard->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Flashcard deleted successfully',
            'data' => $flashcard,
        ]);
    }
}
