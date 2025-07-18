<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UpdateCategoryController extends Controller
{
    public function updateCategory(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'is_active' => 'boolean|default:true',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->description = $request->description;
        $category->color = $request->color;
        $category->icon = $request->icon;
        $category->is_active = $request->is_active;
        $category->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Category updated successfully',
            'data' => $request->user(),
        ]);
    }
}
