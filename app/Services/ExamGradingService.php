<?php

namespace App\Services;

use App\Models\ExamSession;

class ExamGradingService
{
    /**
     * Memvalidasi dan memastikan nilai tidak pernah melebihi 100 dan tidak negatif.
     */
    public function validateAndCapScore(float $score): float
    {
        if ($score > 100.0) {
            return 100.0;
        }

        if ($score < 0.0) {
            return 0.0;
        }

        return round($score, 2);
    }

    /**
     * Menghitung nilai akhir ujian secara dinamis berdasarkan jumlah jawaban benar dan total soal.
     * Hasil akhir dijamin maksimal 100.
     */
    public function calculateScore(int $correctCount, int $totalQuestions): float
    {
        if ($totalQuestions <= 0) {
            return 0.0;
        }

        $rawScore = ($correctCount / $totalQuestions) * 100;

        return $this->validateAndCapScore($rawScore);
    }

    /**
     * Memproses jawaban siswa, mengkoreksi tiap soal, dan menyelesaikan sesi ujian.
     */
    public function finishAndGradeSession(ExamSession $session): float
    {
        $session->load('answers.question');

        $totalQuestions = $session->answers->count();
        $correctCount = 0;

        foreach ($session->answers as $answer) {
            $isCorrect = ($answer->selected_option && $answer->question && $answer->selected_option === $answer->question->correct_option);
            $answer->is_correct = $isCorrect;
            $answer->save();

            if ($isCorrect) {
                $correctCount++;
            }
        }

        $finalScore = $this->calculateScore($correctCount, $totalQuestions);

        $session->update([
            'status' => 'completed',
            'finished_at' => now(),
            'score' => $finalScore,
        ]);

        return $finalScore;
    }
}
