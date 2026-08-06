<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\Question;
use App\Services\ExamService;
use App\Services\QuestionService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExamController extends Controller
{
    public function __construct(
        protected ExamService $examService,
        protected QuestionService $questionService
    ) {}

    public function index()
    {
        return Inertia::render('admin/exams/index', [
            'exams' => $this->examService->getAllExams(),
        ]);
    }

    public function store(StoreExamRequest $request)
    {
        $this->examService->createExam($request->validated(), $request->user()->id);

        return redirect()->back()->with('success', 'Ujian berhasil dibuat!');
    }

    public function show(Request $request, Exam $exam)
    {
        $search = $request->query('bank_search');
        $subject = $request->query('bank_subject');

        return Inertia::render('admin/exams/show', [
            'exam' => $this->examService->getExamWithQuestions($exam),
            'all_bank_questions' => $this->examService->getAllBankQuestionsForExam($exam, $search, $subject),
            'bank_subjects' => $this->questionService->getDistinctSubjects(),
        ]);
    }

    public function update(UpdateExamRequest $request, Exam $exam)
    {
        $this->examService->updateExam($exam, $request->validated());

        return redirect()->back()->with('success', 'Ujian berhasil diperbarui!');
    }

    public function destroy(Exam $exam)
    {
        $this->examService->deleteExam($exam);

        return redirect()->route('admin.exams.index')->with('success', 'Ujian berhasil dihapus!');
    }

    public function results(Exam $exam)
    {
        $data = $this->examService->getExamResults($exam);

        return Inertia::render('admin/exams/results', $data);
    }

    public function attachQuestions(Request $request, Exam $exam)
    {
        $request->validate([
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,id',
        ]);

        $this->examService->attachQuestions($exam, $request->input('question_ids'));

        return redirect()->back()->with('success', 'Soal berhasil ditarik dari Bank Soal ke Ujian ini!');
    }

    public function detachQuestion(Exam $exam, Question $question)
    {
        $this->examService->detachQuestion($exam, $question->id);

        return redirect()->back()->with('success', 'Soal berhasil dilepas dari Ujian!');
    }

    public function attachRandomQuestions(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'subject' => 'nullable|string',
            'count' => 'required|integer|min:1|max:100',
        ]);

        $countAttached = $this->examService->attachRandomQuestions(
            $exam,
            $validated['subject'] ?? null,
            $validated['count']
        );

        if ($countAttached === 0) {
            return redirect()->back()->with('error', 'Tidak ada soal baru yang tersedia untuk ditarik dari Bank Soal.');
        }

        return redirect()->back()->with('success', "Berhasil menarik {$countAttached} soal acak dari Bank Soal!");
    }

    public function attachAllQuestions(Request $request, Exam $exam)
    {
        $subject = $request->input('subject') ?: $exam->subject;
        $count = $this->examService->attachAllQuestionsBySubject($exam, $subject);

        if ($count === 0) {
            return redirect()->back()->with('error', "Tidak ditemukan soal untuk mata pelajaran {$subject} di Bank Soal.");
        }

        return redirect()->back()->with('success', "Berhasil menarik sekaligus SELURUH {$count} soal ({$subject}) ke Ujian ini!");
    }

    public function resetSession(ExamSession $session)
    {
        $userName = $session->user->name;
        $session->answers()->delete();
        $session->delete();

        return redirect()->back()->with('success', "Pengerjaan ujian siswa {$userName} berhasil di-reset! Siswa dapat mengikuti ujian kembali.");
    }

    public function destroySession(ExamSession $session)
    {
        $userName = $session->user->name;
        $session->delete();

        return redirect()->back()->with('success', "Data pengerjaan siswa {$userName} berhasil dihapus!");
    }
}
