<?php

namespace App\Http\Controllers\DailyChalleges;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetChallegeHistoryController extends Controller
{
    public function getChallengeHistory(Request $request)
    {
        $user = $request->user();
        $challenges = $user->dailyChallenges()
            ->orderBy('challenge_date', 'desc')
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $challenges
        ]);
    }
}
