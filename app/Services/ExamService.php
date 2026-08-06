<?php

namespace App\Services;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Collection;

class ExamService
{
    /**
     * Mendapatkan seluruh daftar ujian beserta jumlah soal dan sesi.
     */
    public function getAllExams(): Collection
    {
        return Exam::withCount(['questions', 'sessions'])
            ->latest()
            ->get();
    }

    /**
     * Membuat ujian baru.
     */
    public function createExam(array $data, int $userId): Exam
    {
        $data['created_by'] = $userId;
        $data['is_active'] = $data['is_active'] ?? true;

        return Exam::create($data);
    }

    /**
     * Mendapatkan detail ujian beserta daftar soal.
     */
    public function getExamWithQuestions(Exam $exam): Exam
    {
        return $exam->load(['questions' => function ($q) {
            $q->orderBy('id', 'asc');
        }]);
    }

    /**
     * Memperbarui data ujian.
     */
    public function updateExam(Exam $exam, array $data): bool
    {
        return $exam->update($data);
    }

    /**
     * Menghapus ujian.
     */
    public function deleteExam(Exam $exam): bool
    {
        return $exam->delete();
    }

    /**
     * Menghubungkan/mensinkronkan beberapa soal dari Bank Soal ke Ujian.
     */
    public function attachQuestions(Exam $exam, array $questionIds): void
    {
        $exam->questions()->sync($questionIds);
    }

    /**
     * Melepas soal dari Ujian.
     */
    public function detachQuestion(Exam $exam, int $questionId): void
    {
        $exam->questions()->detach($questionId);
    }

    /**
     * Menarik seluruh soal berdasarkan mata pelajaran sekaligus ke dalam Ujian.
     */
    public function attachAllQuestionsBySubject(Exam $exam, ?string $subject = null): int
    {
        $targetSubject = $subject ?: $exam->subject;
        $query = \App\Models\Question::query();

        if ($targetSubject) {
            $query->where('subject', $targetSubject);
        }

        $allIds = $query->pluck('id')->toArray();

        $exam->questions()->sync($allIds);

        return count($allIds);
    }

    /**
     * Menarik soal acak dari Bank Soal berdasarkan Mata Pelajaran.
     */
    public function attachRandomQuestions(Exam $exam, ?string $subject, int $count): int
    {
        $query = \App\Models\Question::query();

        if ($subject) {
            $query->where('subject', $subject);
        }

        $randomQuestions = $query->inRandomOrder()->limit($count)->pluck('id')->toArray();

        // Sync (replace) so the exam is updated to exactly $count random questions
        $exam->questions()->sync($randomQuestions);

        return count($randomQuestions);
    }

    /**
     * Mendapatkan SELURUH daftar soal di Bank Soal untuk modal penarikan.
     */
    public function getAllBankQuestionsForExam(Exam $exam, ?string $search = null, ?string $subject = null)
    {
        $query = \App\Models\Question::latest();

        if ($search) {
            $query->where('question_text', 'like', "%{$search}%");
        }

        if ($subject) {
            $query->where('subject', $subject);
        }

        return $query->get();
    }

    /**
     * Mendapatkan rekap hasil nilai ujian seluruh siswa.
     */
    public function getExamResults(Exam $exam): array
    {
        $exam->load(['sessions.user', 'sessions.answers', 'questions']);
        $totalExamQuestions = $exam->questions->count();

        return [
            'exam' => [
                'id' => $exam->id,
                'title' => $exam->title,
                'subject' => $exam->subject,
                'duration_minutes' => $exam->duration_minutes,
                'total_questions' => $totalExamQuestions,
            ],
            'sessions' => $exam->sessions->map(function ($session) use ($totalExamQuestions) {
                $totalAnswered = $session->answers->whereNotNull('selected_option')->count();
                $totalUnanswered = max(0, $totalExamQuestions - $totalAnswered);

                return [
                    'id' => $session->id,
                    'user_name' => $session->user->name,
                    'user_email' => $session->user->email,
                    'started_at' => $session->started_at?->format('Y-m-d H:i:s'),
                    'finished_at' => $session->finished_at?->format('Y-m-d H:i:s'),
                    'status' => $session->status,
                    'score' => $session->score,
                    'total_answered' => $totalAnswered,
                    'total_unanswered' => $totalUnanswered,
                    'total_questions' => $totalExamQuestions,
                ];
            }),
        ];
    }
}
