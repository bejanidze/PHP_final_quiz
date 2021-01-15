<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::withCount('questions')->withCount('inCompletedAnswers')->get();
        return response()->json($quizzes);
    }

    public function quiz(Quiz $quiz)
    {
        $question = $quiz->questions()->doesntHave('user_answer')->with('answers',function ($query){
            $query->select(['answer','image','type','question_id']);
        })->first();
        if (!$question){
            return response()->json(['message'=>'Question Not Found'],404);
        }
        $data = [
            'question'=>$question,
            'total'=>$quiz->questions()->count(),
            'incomplete'=>$quiz->questions()->doesntHave('user_answer')->count(),
        ];
        return response()->json($data);
    }

    public function makeAnswer(Quiz $quiz,Request $request)
    {
        $request->validate([
            'question_id'=>'required|numeric',
            'answer_id'=>'required|numeric',
        ]);
        $question = $quiz->questions()->doesntHave('user_answer')->with('answers')->first();
        if (!$question){
            return response()->json(['message'=>'Question Not Found'],404);
        }
        $answer = $question->answers()->where('id','=',$request->answer_id)->first();
        if (!$answer){
            return response()->json(['message'=>'Such Answer Not Found'],404);
        }
        UserAnswer::create([
            'user_id'=>Auth::user()->id,
            'answer_id'=>$answer->id,
            'question_id'=>$question->id,
            'quiz_id'=>$quiz->id,
            'is_correct'=>$answer->is_correct,
        ]);

        return response()->json(['status'=>$answer->is_correct,'message'=>'Answer Successfully Set']);
    }
}
