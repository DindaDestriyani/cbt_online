<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveStudentAnswerRequest;
use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\StudentAnswer;
use App\Services\ExamGradingService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentExamController extends Controller
{
    public function __construct(
        protected ExamGradingService $gradingService
    ) {}
    public function dashboard(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.exams.index');
        }

        $exams = Exam::where('is_active', true)
            ->withCount('questions')
            ->get()
            ->map(function ($exam) use ($user) {
                $session = ExamSession::where('exam_id', $exam->id)
                    ->where('user_id', $user->id)
                    ->first();

                return [
                    'id' => $exam->id,
                    'title' => $exam->title,
                    'subject' => $exam->subject,
                    'duration_minutes' => $exam->duration_minutes,
                    'questions_count' => $exam->questions_count,
                    'session_status' => $session ? $session->status : null,
                    'score' => $session ? $session->score : null,
                ];
            });

        return Inertia::render('student/dashboard', [
            'exams' => $exams,
        ]);
    }

    public function start(Request $request, Exam $exam)
    {
        $user = $request->user();

        if (! $exam->is_active) {
            return redirect()->route('dashboard')->with('error', 'Ujian tidak aktif.');
        }

        $session = ExamSession::firstOrCreate(
            [
                'exam_id' => $exam->id,
                'user_id' => $user->id,
            ],
            [
                'started_at' => now(),
                'status' => 'in_progress',
            ]
        );

        if ($session->status === 'completed') {
            return redirect()->route('student.exams.result', $exam->id);
        }

        // Pre-create student answer rows for all questions
        foreach ($exam->questions as $question) {
            StudentAnswer::firstOrCreate([
                'exam_session_id' => $session->id,
                'question_id' => $question->id,
            ]);
        }

        return redirect()->route('student.exams.room', $exam->id);
    }

    public function room(Request $request, Exam $exam)
    {
        $user = $request->user();

        $session = ExamSession::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->first();

        if (! $session) {
            return redirect()->route('student.exams.start', $exam->id);
        }

        if ($session->status === 'completed') {
            return redirect()->route('student.exams.result', $exam->id);
        }

        // Calculate time left in seconds
        $endTime = $session->started_at->addMinutes($exam->duration_minutes);
        $remainingSeconds = max(0, now()->diffInSeconds($endTime, false));

        if ($remainingSeconds <= 0) {
            $this->gradingService->finishAndGradeSession($session);
            return redirect()->route('student.exams.result', $exam->id);
        }

        $questions = $exam->questions()->orderBy('id', 'asc')->get()->map(function ($q) {
            return [
                'id' => $q->id,
                'question_text' => $q->question_text,
                'option_a' => $q->option_a,
                'option_b' => $q->option_b,
                'option_c' => $q->option_c,
                'option_d' => $q->option_d,
                'option_e' => $q->option_e,
                // Exclude correct_option for security
            ];
        });

        $answers = StudentAnswer::where('exam_session_id', $session->id)
            ->get()
            ->keyBy('question_id');

        return Inertia::render('student/exam-room', [
            'exam' => [
                'id' => $exam->id,
                'title' => $exam->title,
                'subject' => $exam->subject,
                'duration_minutes' => $exam->duration_minutes,
            ],
            'questions' => $questions,
            'answers' => $answers,
            'remaining_seconds' => (int) $remainingSeconds,
            'session_id' => $session->id,
        ]);
    }

    public function saveAnswer(SaveStudentAnswerRequest $request, Exam $exam)
    {
        $user = $request->user();

        $session = ExamSession::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->firstOrFail();

        $validated = $request->validated();

        $answer = StudentAnswer::firstOrNew([
            'exam_session_id' => $session->id,
            'question_id' => $validated['question_id'],
        ]);

        $answer->selected_option = $validated['selected_option'] ?? null;
        $answer->is_flagged = $validated['is_flagged'] ?? false;
        $answer->save();

        return response()->json(['status' => 'success', 'answer' => $answer]);
    }

    public function finish(Request $request, Exam $exam)
    {
        $user = $request->user();

        $session = ExamSession::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->firstOrFail();

        $this->gradingService->finishAndGradeSession($session);

        return redirect()->route('student.exams.result', $exam->id);
    }

    public function result(Request $request, Exam $exam)
    {
        $user = $request->user();

        $session = ExamSession::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->with(['answers.question'])
            ->firstOrFail();

        $totalQuestions = $exam->questions()->count();
        $correctCount = 0;

        $detailedAnswers = $session->answers->map(function ($ans) use (&$correctCount) {
            $isCorrect = $ans->selected_option === $ans->question->correct_option;
            if ($isCorrect) {
                $correctCount++;
            }
            return [
                'question_id' => $ans->question_id,
                'question_text' => $ans->question->question_text,
                'option_a' => $ans->question->option_a,
                'option_b' => $ans->question->option_b,
                'option_c' => $ans->question->option_c,
                'option_d' => $ans->question->option_d,
                'option_e' => $ans->question->option_e,
                'correct_option' => $ans->question->correct_option,
                'selected_option' => $ans->selected_option,
                'is_correct' => $isCorrect,
            ];
        });

        return Inertia::render('student/exam-result', [
            'exam' => [
                'id' => $exam->id,
                'title' => $exam->title,
                'subject' => $exam->subject,
                'duration_minutes' => $exam->duration_minutes,
            ],
            'session' => [
                'started_at' => $session->started_at?->format('H:i:s, d M Y'),
                'finished_at' => $session->finished_at?->format('H:i:s, d M Y'),
                'score' => $session->score,
                'status' => $session->status,
            ],
            'total_questions' => $totalQuestions,
            'correct_count' => $correctCount,
            'incorrect_count' => $totalQuestions - $correctCount,
            'details' => $detailedAnswers,
        ]);
    }

}
