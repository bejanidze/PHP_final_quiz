<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class AdminController extends Controller
{
    public function getUsers()
    {
        $users = User::orderBy('id','desc')->where('role','=',0)->get();
        return response()->json(['users'=>$users]);
    }

    public function getQuizzes()
    {
        $quizzes = Quiz::withCount('questions')->get();
        return response()->json(['quizzes'=>$quizzes]);
    }

    public function addQuiz(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'description'=>'nullable|max:500',
            'cover'=>'required|image|mimes:jpeg,png,jpg,gi|max:2048'
        ]);

        $data = $request->only(['name','description']);

        $imageName = time().'.'.$request->cover->extension();
        $request->cover->move(public_path('images'), $imageName);

        $data['cover'] = $imageName;

        $quiz = Quiz::create($data);
        return response()->json(['quiz'=>$quiz,'status'=>true,'message'=>'Quiz added successfully']);
    }

    public function getQuestions($id,Request $request)
    {
        $quiz = Quiz::find($id);
        if (!$quiz){
            return response()->json(['status'=>false,'message'=>'Such Quiz Not Found'],404);
        }
        $questions = $quiz->questions()->with('answers')->get();

        return response()->json(['status'=>true,'questions'=>$questions]);
    }

    public function updateQuizQuestions($id,Request $request)
    {
        $request->validate([
            'questions*.title'=>'required|string|max:255',
            'questions*.description'=>'nullable|max:500',
            'questions*.answers.*.answer'=>'required',
            'questions*.answers.*.is_correct'=>'required|boolean',
        ]);
        $quiz = Quiz::find($id);

        foreach ($request->questions as $k=>$question)
        {
            $quest = Question::where('id','=',$question['id'])->where('quiz_id','=',$quiz->id)->first();
            if ($quest){
                $quest->update([
                    'title'=>$question['title'],
                    'description'=>$question['description'],
                    'quiz_id'=>$quiz->id,
                ]);

                $quest->answers()->update($question['answers']);
            }else{
                $quest = Question::create($question);
                $quest->answers()->save($question->answers);
            }
        }

        return response()->json(['status'=>true,'message'=>'updated successfully']);

    }
}
