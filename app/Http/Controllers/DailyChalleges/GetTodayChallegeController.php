<?php

namespace App\Http\Controllers\DailyChalleges;

use App\Http\Controllers\Controller;
use App\Models\DailyChallenge;
use App\Models\Flashcard;
use Illuminate\Http\Request;

class GetTodayChallegeController extends Controller
{
    public function getTodayChallenge(Request $request)
    {
        $user = $request->user();
        $challenge = $user->getTodayChallenge();

        if (!$challenge) {
            $challenge = $this->createTodayChallenge($user);
        }

        return response()->json([
            'status' => 'success',
            'data' => $challenge
        ]);
    }

    private function createTodayChallenge($user): DailyChallenge
    {
        // Get random flashcards for challenge
        $flashcards = Flashcard::active()
            ->with('category')
            ->inRandomOrder()
            ->limit(5)
            ->get();

        $questions = [];
        foreach ($flashcards as $flashcard) {
            // Create multiple choice options
            $options = $this->generateMultipleChoiceOptions($flashcard);

            $questions[] = [
                'flashcard_id' => $flashcard->id,
                'question' => $flashcard->question,
                'options' => $options,
                'correct_answer' => 0, // First option is always correct
                'difficulty' => $flashcard->difficulty,
                'category' => $flashcard->category->name
            ];
        }

        return DailyChallenge::createTodayChallenge($user, $questions);
    }

    private function generateMultipleChoiceOptions(Flashcard $flashcard): array
    {
        $options = [$flashcard->answer]; // Correct answer

        // Get 3 random wrong answers from same category
        $wrongAnswers = Flashcard::where('category_id', $flashcard->category_id)
            ->where('id', '!=', $flashcard->id)
            ->active()
            ->inRandomOrder()
            ->limit(3)
            ->pluck('answer')
            ->toArray();

        // Fill remaining slots with generic wrong answers if needed
        while (count($wrongAnswers) < 3) {
            $wrongAnswers[] = 'Option ' . (count($wrongAnswers) + 1);
        }

        $options = array_merge($options, $wrongAnswers);
        shuffle($options);

        // Find correct answer index after shuffle
        $correctIndex = array_search($flashcard->answer, $options);

        return [
            'options' => $options,
            'correct_index' => $correctIndex
        ];
    }
}
