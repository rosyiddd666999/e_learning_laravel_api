<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class DeleteCategoryController extends Controller
{
    public function deleteCategory(Request $request) {
        $category = Category::find($request->id);
        $category->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Category deleted successfully',
            'data' => $request->user(),
        ]);
    }
}
