<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\models\Question;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $question  = Question::all();
        return response()->json([
            "question : "=> $question,
        ]);
    }

    public function store(Request $request)
    {
        $messages = [
            'question.required' => 'السوال مطلوب.',
            'question.string' => 'يجب أن يكون السوال نصًا.',
            'answer.required' => 'الاجابة مطلوبة.',
            'answer.string' => 'يجب أن تكون الاجابة نصًا.',
        ];

        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'answer' => 'required|string',
        ], messages: $messages);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
            ], 422);
        };

        $question = Question::create([
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        return response()->json($question, 200);
    }

    public function show($id)
    {
        $question = Question::find($id);
        return response()->json($question);
    }

    public function update(Request $request, $id)
    {
        $messages = [
            'question.required' => 'السوال مطلوب.',
            'question.string' => 'يجب أن يكون السوال نصًا.',
            'answer.required' => 'الاجابة مطلوبة.',
            'answer.string' => 'يجب أن تكون الاجابة نصًا.',
        ];

        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'answer' => 'required|string',
        ], messages: $messages);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
            ], 422);
        };

        $question = question::findOrFail($id)->update([
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        return response()->json($question);
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();
        return response()->json([
            'succec' => 'بنجاح [ ' . $question->name . ' ] تم حذف  ',
        ]);
    }
}