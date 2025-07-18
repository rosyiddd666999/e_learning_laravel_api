<?php

namespace App\Http\Controllers\Progress;

use App\Http\Controllers\Controller;
use App\Models\UserProgress;
use Illuminate\Http\Request;

class GetLeaderboardController extends Controller
{
    public function getLeaderboard(Request $request)
    {
        $period = $request->get('period', 'all_time');

        $query = UserProgress::query()
            ->selectRaw('user_id, SUM(total_points) as total_points, SUM(completed_cards) as total_cards')
            ->with('user:id,name')
            ->groupBy('user_id')
            ->orderBy('total_points', 'desc');

        if ($period === 'weekly') {
            $query->where('updated_at', '>=', now()->subWeek());
        } elseif ($period === 'monthly') {
            $query->where('updated_at', '>=', now()->subMonth());
        }

        $leaderboard = $query->limit(10)->get();

        return response()->json([
            'status' => 'success',
            'data' => $leaderboard
        ]);
    }
}
