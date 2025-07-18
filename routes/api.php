<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'forgotPassword']);
Route::post('/reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'resetPassword']);

Route::get('/categories', [App\Http\Controllers\Category\GetCategoryController::class, 'index']);
Route::get('/categories/{category}', [App\Http\Controllers\Category\GetCategoryController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Auth\LogoutController::class, 'logout']);
    Route::get('/user', [App\Http\Controllers\Users\GetUserController::class, 'getUser']);

    Route::prefix('/categories')->group(function () {
        Route::post('/', [App\Http\Controllers\Category\CreateCategoryController::class, 'createCategory']);
        Route::put('/{category}', [App\Http\Controllers\Category\UpdateCategoryController::class, 'updateCategory']);
        Route::delete('/{category}', [App\Http\Controllers\Category\DeleteCategoryController::class, 'deleteCategory']);
        Route::get('/{category}/progress', [App\Http\Controllers\Category\GetCategoryController::class, 'getUserProgressCategory']);
    });

    Route::prefix('/flashcards')->group(function () {
        Route::get('/', [App\Http\Controllers\Flashcard\GetFlashcardController::class, 'index']);
        Route::post('/', [App\Http\Controllers\Flashcard\CreateFlashcardController::class, 'createFlashcard']);
        Route::put('/{flashcard}', [App\Http\Controllers\Flashcard\UpdateFlashcardController::class, 'updateFlashcard']);
        Route::delete('/{flashcard}', [App\Http\Controllers\Flashcard\DeleteFlashcardController::class, 'deleteFlashcard']);
        Route::get('/random', [App\Http\Controllers\Flashcard\GetFlashcardController::class, 'getRandomCards']);
        Route::get('/{flashcard}', [App\Http\Controllers\Flashcard\GetFlashcardController::class, 'show']);
        Route::post('/attempt/{flashcard}', [App\Http\Controllers\Flashcard\GetFlashcardController::class, 'attempt']);
        Route::get('/attempt/{flashcard}', [App\Http\Controllers\Flashcard\GetFlashcardController::class, 'getUserAttempts']);
        Route::get('/{category}', [App\Http\Controllers\Flashcard\GetFlashcardController::class, 'getByCategory']);
    });
});
