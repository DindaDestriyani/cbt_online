<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Models\Question;
use App\Services\QuestionService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuestionController extends Controller
{
    public function __construct(
        protected QuestionService $questionService
    ) {}

    public function index(Request $request)
    {
        $search = $request->query('search');
        $subject = $request->query('subject');

        return Inertia::render('admin/questions/index', [
            'questions' => $this->questionService->getBankQuestions($search, $subject),
            'subjects' => $this->questionService->getDistinctSubjects(),
            'filters' => [
                'search' => $search ?? '',
                'subject' => $subject ?? '',
            ],
        ]);
    }

    public function store(StoreQuestionRequest $request)
    {
        $this->questionService->createQuestion($request->validated());

        return redirect()->back()->with('success', 'Soal berhasil ditambahkan ke Bank Soal!');
    }

    public function update(UpdateQuestionRequest $request, Question $question)
    {
        $this->questionService->updateQuestion($question, $request->validated());

        return redirect()->back()->with('success', 'Soal di Bank Soal berhasil diperbarui!');
    }

    public function destroy(Question $question)
    {
        $this->questionService->deleteQuestion($question);

        return redirect()->back()->with('success', 'Soal berhasil dihapus dari Bank Soal!');
    }
}
