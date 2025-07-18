<?php

namespace App\Http\Controllers\DailyChalleges;

use App\Http\Controllers\Controller;
use App\Models\StreakHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubmitChallegeController extends Controller
{
    public function submitChallenge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'answers' => 'required|array',
            'answers.*' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $challenge = $user->getTodayChallenge();

        if (!$challenge) {
            return response()->json([
                'status' => 'error',
                'message' => 'No challenge found for today'
            ], 404);
        }

        if ($challenge->is_completed) {
            return response()->json([
                'status' => 'error',
                'message' => 'Challenge already completed'
            ], 400);
        }

        $answers = $request->answers;
        $questions = $challenge->questions_data;
        $correctAnswers = 0;

        foreach ($answers as $index => $answer) {
            if (isset($questions[$index]) && $questions[$index]['correct_answer'] == $answer) {
                $correctAnswers++;
            }
        }

        $challenge->complete($correctAnswers);

        // Record streak activity
        StreakHistory::recordActivity(
            $user,
            $correctAnswers * 5,
            $correctAnswers
        );

        $user->updateStreak();

        return response()->json([
            'status' => 'success',
            'message' => 'Challenge completed successfully!',
            'data' => [
                'challenge' => $challenge->fresh(),
                'correct_answers' => $correctAnswers,
                'total_questions' => $challenge->total_questions,
                'score' => $challenge->score,
                'points_earned' => $correctAnswers * 5,
                'current_streak' => $user->fresh()->current_streak
            ]
        ]);
    }
}
