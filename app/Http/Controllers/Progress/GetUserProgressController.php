<?php

namespace App\Http\Controllers\Progress;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetUserProgressController extends Controller
{
    public function getUserProgress(Request $request)
    {
        $user = $request->user();

        $progress = $user->userProgress()->with('category')->get();

        $totalStats = [
            'total_points' => $user->total_points,
            'current_streak' => $user->current_streak,
            'categories_completed' => $progress->count(),
            'total_cards_completed' => $progress->sum('completed_cards'),
            'overall_accuracy' => $progress->avg('accuracy_percentage') ?: 0,
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'progress' => $progress,
                'statistics' => $totalStats
            ]
        ]);
    }
}
