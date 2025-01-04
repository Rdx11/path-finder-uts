<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Answer;
use App\Models\Criteria;
use App\Models\Result;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    public function showForm()
    {
        $questions = Question::with('criteria')->get();
        return view('questionnaire', compact('questions'));
    }

    public function submitForm(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:answers,email',
            'answers' => 'required|array',
            'answers.*' => 'integer|min:1|max:5',
        ]);

        foreach ($data['answers'] as $questionId => $answer) {
            Answer::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'question_id' => $questionId,
                'answer' => $answer,
            ]);
        }

        return redirect()->route('result', ['email' => $data['email']]);
    }

    public function calculateResult($email)
    {
        $answers = Answer::where('email', $email)->get();
        $criteria = Criteria::all();

        $maxValues = $criteria->mapWithKeys(function ($item) use ($answers) {
            return [$item->id => $answers->where('question.criteria_id', $item->id)->max('answer')];
        });

        $finalScore = $criteria->reduce(function ($carry, $item) use ($answers, $maxValues) {
            $value = $answers->where('question.criteria_id', $item->id)->pluck('answer')->first();
            $normalized = $value / $maxValues[$item->id];
            return $carry + ($normalized * $item->weight);
        }, 0);

        $recommendedMajor = $this->determineMajor($finalScore);

        Result::create([
            'name' => $answers->first()->name,
            'email' => $email,
            'recommended_major' => $recommendedMajor,
            'score' => $finalScore,
        ]);

        return view('result', compact('recommendedMajor', 'finalScore'));
    }
}
