<?php

namespace App\Http\Controllers\Flashcard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Flashcard;
use App\Models\UserFlashcardAttempt;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GetFlashcardController extends Controller
{
    public function index(Request $request)
    {
        $query = Flashcard::active()->with('category');

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('difficulty')) {
            $query->byDifficulty($request->difficulty);
        }

        $flashcards = $query->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $flashcards
        ]);
    }

    public function show(Flashcard $flashcard)
    {
        if (!$flashcard->is_active) {
            return response()->json([
                'status' => 'error',
                'message' => 'Flashcard not found'
            ], 404);
        }

        $flashcard->load('category');

        return response()->json([
            'status' => 'success',
            'data' => $flashcard
        ]);
    }

    public function getByCategory(Category $category)
    {
        $flashcards = $category->flashcards()
            ->active()
            ->inRandomOrder()
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $flashcards
        ]);
    }

    public function attempt(Request $request, Flashcard $flashcard)
    {
        $validator = Validator::make($request->all(), [
            'is_correct' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $isCorrect = $request->boolean('is_correct');

        // Record attempt
        $attempt = UserFlashcardAttempt::updateOrCreate([
            'user_id' => $user->id,
            'flashcard_id' => $flashcard->id,
        ]);

        $attempt->recordAttempt($isCorrect);

        // Update user progress
        $progress = UserProgress::firstOrCreate([
            'user_id' => $user->id,
            'category_id' => $flashcard->category_id,
        ], [
            'completed_cards' => 0,
            'total_points' => 0,
            'correct_answers' => 0,
            'wrong_answers' => 0,
            'accuracy_percentage' => 0,
        ]);

        if ($isCorrect) {
            $progress->addCorrectAnswer(10); // 10 points per correct answer
            $user->addPoints(10);
            $user->updateStreak();
        } else {
            $progress->addWrongAnswer();
        }

        return response()->json([
            'status' => 'success',
            'message' => $isCorrect ? 'Correct answer!' : 'Wrong answer, try again!',
            'data' => [
                'points_earned' => $isCorrect ? 10 : 0,
                'total_points' => $user->fresh()->total_points,
                'current_streak' => $user->fresh()->current_streak,
                'progress' => $progress->fresh()
            ]
        ]);
    }

    public function getRandomCards(Request $request)
    {
        $limit = $request->get('limit', 10);
        $categoryId = $request->get('category_id');

        $query = Flashcard::active()->with('category');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $flashcards = $query->inRandomOrder()->limit($limit)->get();

        return response()->json([
            'status' => 'success',
            'data' => $flashcards
        ]);
    }

    public function getUserAttempts(Request $request, Flashcard $flashcard)
    {
        $user = $request->user();

        $attempt = UserFlashcardAttempt::where('user_id', $user->id)
            ->where('flashcard_id', $flashcard->id)
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => $attempt
        ]);
    }
}
