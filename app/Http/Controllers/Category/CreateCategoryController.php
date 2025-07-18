<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CreateCategoryController extends Controller
{
    public function createCategory(Request $request) {
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

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'color' => $request->color,
            'icon' => $request->icon,
            'is_active' => $request->is_active,
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully',
            'data' => $category,
        ]);
    }
}
