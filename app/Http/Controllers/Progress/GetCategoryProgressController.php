<?php

namespace App\Http\Controllers\Progress;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetCategoryProgressController extends Controller
{
    public function getCategoryProgress(Request $request, $id)
    {
        $user = $request->user();

        $progress = $user->userProgress()
            ->where('category_id', $id)
            ->with('category')
            ->first();

        if (!$progress) {
            return response()->json([
                'status' => 'error',
                'message' => 'Progress not found for this category'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $progress
        ]);
    }
}
