<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetUserController extends Controller
{
    public function getUser(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'User fetched successfully',
            'data' => $request->user(),
        ]);
    }
}
