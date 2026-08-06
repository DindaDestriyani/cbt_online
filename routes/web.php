<?php

use App\Http\Controllers\ExamController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StudentExamController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsSiswa;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [StudentExamController::class, 'dashboard'])->name('dashboard');

    // Student Exam Routes
    Route::middleware([EnsureUserIsSiswa::class])->prefix('student')->name('student.')->group(function () {
        Route::get('exams/{exam}/start', [StudentExamController::class, 'start'])->name('exams.start');
        Route::get('exams/{exam}/room', [StudentExamController::class, 'room'])->name('exams.room');
        Route::post('exams/{exam}/save-answer', [StudentExamController::class, 'saveAnswer'])->name('exams.save-answer');
        Route::post('exams/{exam}/finish', [StudentExamController::class, 'finish'])->name('exams.finish');
        Route::get('exams/{exam}/result', [StudentExamController::class, 'result'])->name('exams.result');
    });

    // Admin Routes
    Route::middleware([EnsureUserIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
        // Exams Management
        Route::get('exams', [ExamController::class, 'index'])->name('exams.index');
        Route::post('exams', [ExamController::class, 'store'])->name('exams.store');
        Route::get('exams/{exam}', [ExamController::class, 'show'])->name('exams.show');
        Route::put('exams/{exam}', [ExamController::class, 'update'])->name('exams.update');
        Route::delete('exams/{exam}', [ExamController::class, 'destroy'])->name('exams.destroy');
        Route::get('exams/{exam}/results', [ExamController::class, 'results'])->name('exams.results');

        // Pull / Attach / Detach Questions for Exam
        Route::post('exams/{exam}/attach-questions', [ExamController::class, 'attachQuestions'])->name('exams.attach-questions');
        Route::delete('exams/{exam}/questions/{question}/detach', [ExamController::class, 'detachQuestion'])->name('exams.detach-question');
        Route::post('exams/{exam}/random-questions', [ExamController::class, 'attachRandomQuestions'])->name('exams.random-questions');
        Route::post('exams/{exam}/attach-all', [ExamController::class, 'attachAllQuestions'])->name('exams.attach-all');

        // Bank Soal Master Management
        Route::get('questions', [QuestionController::class, 'index'])->name('questions.index');
        Route::post('questions', [QuestionController::class, 'store'])->name('questions.store');
        Route::put('questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
        Route::delete('questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

        // Reset / Delete Student Exam Session
        Route::post('exam-sessions/{session}/reset', [ExamController::class, 'resetSession'])->name('exam-sessions.reset');
        Route::delete('exam-sessions/{session}', [ExamController::class, 'destroySession'])->name('exam-sessions.destroy');
    });
});

require __DIR__.'/settings.php';
