<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JwtAuthController;
use App\Http\Controllers\QuizController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('/signup', [JwtAuthController::class, 'register']);
    Route::post('/signin', [JwtAuthController::class, 'login']);
    Route::get('/user', [JwtAuthController::class, 'user']);
    Route::post('/token-refresh', [JwtAuthController::class, 'refresh']);
    Route::post('/signout', [JwtAuthController::class, 'signout']);

});
Route::group(['middleware' => ['api','auth']],function ($router){
    Route::get('/quizzes',[QuizController::class,'index']);
    Route::get('/quizzes/{quiz}',[QuizController::class,'quiz']);
    Route::post('/quizzes/{quiz}',[QuizController::class,'makeAnswer']);

    Route::group(['middleware'=>['admin']],function (){
        Route::get('getUsers',[AdminController::class,'getUsers']);
        Route::get('getQuizzes',[AdminController::class,'getQuizzes']);
        Route::post('addQuiz',[AdminController::class,'addQuiz']);
        Route::get('getQuizzes/{id}',[AdminController::class,'getQuestions']);
        Route::post('updateQuizQuestions/{id}',[AdminController::class,'updateQuizQuestions']);
    });
});

