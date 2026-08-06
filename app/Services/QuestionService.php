<?php

namespace App\Services;

use App\Models\Question;

class QuestionService
{
    /**
     * Mendapatkan seluruh daftar Bank Soal dengan pencarian & filter mata pelajaran.
     */
    public function getBankQuestions(?string $search = null, ?string $subject = null)
    {
        $query = Question::withCount('exams')->latest();

        if ($search) {
            $query->where('question_text', 'like', "%{$search}%");
        }

        if ($subject) {
            $query->where('subject', $subject);
        }

        return $query->paginate(15)->withQueryString();
    }

    /**
     * Mendapatkan daftar semua mata pelajaran yang ada di Bank Soal.
     */
    public function getDistinctSubjects(): array
    {
        return Question::whereNotNull('subject')
            ->where('subject', '!=', '')
            ->distinct()
            ->pluck('subject')
            ->toArray();
    }

    /**
     * Membuat soal baru di Bank Soal.
     */
    public function createQuestion(array $data): Question
    {
        return Question::create($data);
    }

    /**
     * Memperbarui data soal.
     */
    public function updateQuestion(Question $question, array $data): bool
    {
        return $question->update($data);
    }

    /**
     * Menghapus soal dari Bank Soal.
     */
    public function deleteQuestion(Question $question): bool
    {
        return $question->delete();
    }
}
