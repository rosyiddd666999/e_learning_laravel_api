<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class GetCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::active()
            ->withCount(['flashcards' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }

    public function show(Category $category)
    {
        if (!$category->is_active) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found'
            ], 404);
        }

        $category->load(['flashcards' => function ($query) {
            $query->where('is_active', true);
        }]);

        $category->loadCount(['flashcards' => function ($query) {
            $query->where('is_active', true);
        }]);

        return response()->json([
            'status' => 'success',
            'data' => $category
        ]);
    }

    public function getUserProgressCategory(Request $request, Category $category)
    {
        $user = $request->user();

        $progress = $user->userProgress()
            ->where('category_id', $category->id)
            ->first();

        if (!$progress) {
            $progress = $user->userProgress()->create([
                'category_id' => $category->id,
                'completed_cards' => 0,
                'total_points' => 0,
                'correct_answers' => 0,
                'wrong_answers' => 0,
                'accuracy_percentage' => 0,
            ]);
        }

        $totalCards = $category->getTotalFlashcards();
        $completionPercentage = $totalCards > 0 ? ($progress->completed_cards / $totalCards) * 100 : 0;

        return response()->json([
            'status' => 'success',
            'data' => [
                'progress' => $progress,
                'total_cards' => $totalCards,
                'completion_percentage' => round($completionPercentage, 2)
            ]
        ]);
    }
}
