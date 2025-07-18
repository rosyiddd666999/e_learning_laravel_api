<?php

namespace App\Http\Controllers\DailyChalleges;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetStreakHistoryController extends Controller
{
    public function getStreakHistory(Request $request)
    {
        $user = $request->user();
        $streakHistory = $user->streakHistory()
            ->orderBy('activity_date', 'desc')
            ->paginate(30);

        return response()->json([
            'status' => 'success',
            'data' => $streakHistory
        ]);
    }
}
