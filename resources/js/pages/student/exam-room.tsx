import { useState, useEffect } from 'react';
import { Head, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Badge } from '@/components/ui/badge';
import { Clock, Flag, ChevronLeft, ChevronRight, CheckCircle2, AlertTriangle, Send, BookOpen } from 'lucide-react';

interface Question {
    id: number;
    question_text: string;
    option_a: string;
    option_b: string;
    option_c: string;
    option_d: string;
    option_e: string;
}

interface AnswerState {
    selected_option: string | null;
    is_flagged: boolean;
}

interface Props {
    exam: {
        id: number;
        title: string;
        subject: string;
        duration_minutes: number;
    };
    questions: Question[];
    answers: Record<number, AnswerState>;
    remaining_seconds: number;
    session_id: number;
}

export default function StudentExamRoom({ exam, questions, answers: initialAnswers, remaining_seconds }: Props) {
    const [currentIndex, setCurrentIndex] = useState(0);
    const [localAnswers, setLocalAnswers] = useState<Record<number, AnswerState>>(() => {
        const map: Record<number, AnswerState> = {};
        questions.forEach(q => {
            map[q.id] = {
                selected_option: initialAnswers[q.id]?.selected_option || null,
                is_flagged: Boolean(initialAnswers[q.id]?.is_flagged),
            };
        });
        return map;
    });

    const [timeLeft, setTimeLeft] = useState(remaining_seconds);
    const [isFinishDialogOpen, setIsFinishDialogOpen] = useState(false);
    const [isSubmitting, setIsSubmitting] = useState(false);

    const currentQuestion = questions[currentIndex];
    const currentAnswer = localAnswers[currentQuestion?.id] || { selected_option: null, is_flagged: false };

    // Countdown Timer logic
    useEffect(() => {
        if (timeLeft <= 0) {
            handleAutoFinish();
            return;
        }

        const timer = setInterval(() => {
            setTimeLeft(prev => {
                if (prev <= 1) {
                    clearInterval(timer);
                    handleAutoFinish();
                    return 0;
                }
                return prev - 1;
            });
        }, 1000);

        return () => clearInterval(timer);
    }, [timeLeft]);

    const formatTimer = (seconds: number) => {
        const m = Math.floor(seconds / 60);
        const s = seconds % 60;
        return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
    };

    const handleSelectOption = (option: string) => {
        if (!currentQuestion) return;

        const updatedAnswer = {
            ...currentAnswer,
            selected_option: currentAnswer.selected_option === option ? null : option,
        };

        setLocalAnswers(prev => ({
            ...prev,
            [currentQuestion.id]: updatedAnswer,
        }));

        saveAnswerToBackend(currentQuestion.id, updatedAnswer.selected_option, updatedAnswer.is_flagged);
    };

    const handleToggleFlag = () => {
        if (!currentQuestion) return;

        const updatedAnswer = {
            ...currentAnswer,
            is_flagged: !currentAnswer.is_flagged,
        };

        setLocalAnswers(prev => ({
            ...prev,
            [currentQuestion.id]: updatedAnswer,
        }));

        saveAnswerToBackend(currentQuestion.id, updatedAnswer.selected_option, updatedAnswer.is_flagged);
    };

    const saveAnswerToBackend = (questionId: number, selectedOption: string | null, isFlagged: boolean) => {
        fetch(`/student/exams/${exam.id}/save-answer`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
            },
            body: JSON.stringify({
                question_id: questionId,
                selected_option: selectedOption,
                is_flagged: isFlagged,
            }),
        }).catch(err => console.error('Failed to auto-save answer:', err));
    };

    const handleAutoFinish = () => {
        setIsSubmitting(true);
        router.post(`/student/exams/${exam.id}/finish`);
    };

    const handleConfirmFinish = () => {
        setIsSubmitting(true);
        router.post(`/student/exams/${exam.id}/finish`);
    };

    const answeredCount = Object.values(localAnswers).filter(a => a.selected_option !== null).length;
    const flaggedCount = Object.values(localAnswers).filter(a => a.is_flagged).length;
    const isTimeWarning = timeLeft < 300; // less than 5 minutes

    return (
        <div className="min-h-screen bg-slate-50 flex flex-col font-sans select-none">
            <Head title={`Ruang Ujian - ${exam.title}`} />

            {/* Clean Bright Header Navigation Bar */}
            <header className="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-4 lg:px-8 py-3.5 flex items-center justify-between shadow-xs">
                <div className="flex items-center gap-3">
                    <div className="p-2.5 bg-blue-50 text-blue-600 rounded-2xl border border-blue-100 shrink-0">
                        <BookOpen className="size-6" />
                    </div>
                    <div>
                        <h1 className="text-base lg:text-lg font-black text-slate-900 line-clamp-1">
                            {exam.title}
                        </h1>
                        <p className="text-xs font-bold text-blue-600">
                            {exam.subject} • Soal Nomor {currentIndex + 1} dari {questions.length}
                        </p>
                    </div>
                </div>

                <div className="flex items-center gap-3 sm:gap-5">
                    {/* Timer Badge */}
                    <div
                        className={`flex items-center gap-2 px-4 py-2 rounded-2xl border-2 font-mono text-base font-black shadow-xs ${
                            isTimeWarning
                                ? 'bg-rose-50 border-rose-400 text-rose-600 animate-pulse'
                                : 'bg-blue-50 border-blue-200 text-blue-800'
                        }`}
                    >
                        <Clock className={`size-4 ${isTimeWarning ? 'text-rose-500' : 'text-blue-600'}`} />
                        <span>{formatTimer(timeLeft)}</span>
                    </div>

                    <Button
                        onClick={() => setIsFinishDialogOpen(true)}
                        className="bg-emerald-600 hover:bg-emerald-700 text-white font-bold gap-2 shadow-md shadow-emerald-600/20 text-xs sm:text-sm rounded-2xl px-5 py-2.5 h-11"
                    >
                        <Send className="size-4" />
                        <span className="hidden sm:inline">Selesai Ujian</span>
                    </Button>
                </div>
            </header>

            {/* Main Content Area */}
            <main className="flex-1 max-w-7xl w-full mx-auto p-4 lg:p-6 grid grid-cols-1 lg:grid-cols-4 gap-6">
                {/* Left/Center: Question Card */}
                <div className="lg:col-span-3 flex flex-col justify-between space-y-6">
                    <Card className="border border-slate-200 shadow-md shadow-blue-950/5 bg-white flex-1 flex flex-col rounded-3xl overflow-hidden">
                        <CardHeader className="border-b border-slate-100 bg-slate-50/80 py-4 px-6 flex flex-row items-center justify-between">
                            <Badge className="bg-blue-600 text-white text-sm px-4 py-1.5 font-black rounded-xl shadow-xs">
                                Soal Nomor {currentIndex + 1}
                            </Badge>

                            <button
                                onClick={handleToggleFlag}
                                className={`flex items-center gap-2 px-4 py-2 rounded-2xl text-xs font-bold border-2 transition-all ${
                                    currentAnswer.is_flagged
                                        ? 'bg-amber-400 border-amber-500 text-slate-950 shadow-md shadow-amber-400/20'
                                        : 'bg-white border-slate-200 text-slate-600 hover:bg-amber-50 hover:border-amber-300'
                                }`}
                            >
                                <Flag className={`size-4 ${currentAnswer.is_flagged ? 'fill-slate-950 text-slate-950' : 'text-amber-500'}`} />
                                <span>{currentAnswer.is_flagged ? 'Ragu-Ragu (Tersimpan)' : 'Tandai Ragu-Ragu'}</span>
                            </button>
                        </CardHeader>

                        <CardContent className="p-6 sm:p-8 space-y-6 flex-1">
                            {/* Question text */}
                            <div className="text-base sm:text-xl text-slate-900 leading-relaxed whitespace-pre-line font-bold">
                                {currentQuestion?.question_text}
                            </div>

                            {/* Options A - E */}
                            <div className="space-y-3.5 pt-3">
                                {(['a', 'b', 'c', 'd', 'e'] as const).map(optKey => {
                                    const optionText = currentQuestion?.[`option_${optKey}`];
                                    const isSelected = currentAnswer.selected_option === optKey;

                                    return (
                                        <div
                                            key={optKey}
                                            onClick={() => handleSelectOption(optKey)}
                                            className={`group relative flex items-start gap-4 p-4 sm:p-5 rounded-2xl border-2 cursor-pointer transition-all ${
                                                isSelected
                                                    ? 'border-blue-600 bg-blue-50/80 shadow-md shadow-blue-600/10 scale-[1.005]'
                                                    : 'border-slate-200 hover:border-blue-300 bg-white hover:bg-slate-50/50'
                                            }`}
                                        >
                                            <div
                                                className={`size-9 rounded-xl text-base font-black flex items-center justify-center shrink-0 uppercase transition-all ${
                                                    isSelected
                                                        ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25'
                                                        : 'bg-slate-100 text-slate-700 group-hover:bg-slate-200'
                                                }`}
                                            >
                                                {optKey}
                                            </div>

                                            <div className="flex-1 text-sm sm:text-base text-slate-900 pt-1 leading-snug font-semibold">
                                                {optionText}
                                            </div>

                                            {isSelected && (
                                                <CheckCircle2 className="size-6 text-blue-600 shrink-0 self-center" />
                                            )}
                                        </div>
                                    );
                                })}
                            </div>
                        </CardContent>
                    </Card>

                    {/* Bottom Navigation Toolbar */}
                    <div className="flex items-center justify-between gap-4 bg-white p-4 rounded-3xl border border-slate-200 shadow-md shadow-blue-950/5">
                        <Button
                            variant="outline"
                            onClick={() => setCurrentIndex(prev => Math.max(0, prev - 1))}
                            disabled={currentIndex === 0}
                            className="gap-2 rounded-2xl font-bold h-11 px-5 border-slate-200"
                        >
                            <ChevronLeft className="size-5" />
                            Sebelumnya
                        </Button>

                        <Button
                            onClick={() => {
                                if (currentIndex < questions.length - 1) {
                                    setCurrentIndex(prev => prev + 1);
                                } else {
                                    setIsFinishDialogOpen(true);
                                }
                            }}
                            className="gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-lg shadow-blue-600/20 rounded-2xl h-11 px-6"
                        >
                            {currentIndex === questions.length - 1 ? (
                                <>
                                    <span>Selesai & Kumpulkan</span>
                                    <Send className="size-4" />
                                </>
                            ) : (
                                <>
                                    <span>Selanjutnya</span>
                                    <ChevronRight className="size-5" />
                                </>
                            )}
                        </Button>
                    </div>
                </div>

                {/* Right: Question Navigation Grid Sidebar (UKURAN NOMOR DIBERBESAR) */}
                <div className="space-y-4">
                    <Card className="border border-slate-200 shadow-md shadow-blue-950/5 bg-white rounded-3xl">
                        <CardHeader className="pb-3 border-b border-slate-100">
                            <CardTitle className="text-base font-extrabold text-slate-900 flex items-center justify-between">
                                <span>Navigasi Soal</span>
                                <Badge variant="secondary" className="text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                    {answeredCount}/{questions.length} Terjawab
                                </Badge>
                            </CardTitle>
                        </CardHeader>
                        <CardContent className="pt-4 space-y-4">
                            {/* Grid of Numbers (DIBERBESAR: size-12 / h-12 w-12 & text-base) */}
                            <div className="grid grid-cols-5 gap-2.5 max-h-[420px] overflow-y-auto pr-1">
                                {questions.map((q, idx) => {
                                    const ans = localAnswers[q.id];
                                    const isCurrent = idx === currentIndex;
                                    const isAnswered = Boolean(ans?.selected_option);
                                    const isFlagged = Boolean(ans?.is_flagged);

                                    let bgClasses = 'bg-slate-100 text-slate-800 hover:bg-slate-200 border border-slate-200';
                                    if (isFlagged) {
                                        bgClasses = 'bg-amber-400 text-slate-950 font-black border-2 border-amber-500 shadow-md shadow-amber-400/20';
                                    } else if (isAnswered) {
                                        bgClasses = 'bg-blue-600 text-white font-black shadow-md shadow-blue-600/30 border-2 border-blue-600';
                                    }

                                    return (
                                        <button
                                            key={q.id}
                                            onClick={() => setCurrentIndex(idx)}
                                            className={`h-12 w-full rounded-2xl text-base sm:text-lg transition-all flex items-center justify-center relative font-black ${bgClasses} ${
                                                isCurrent ? 'ring-4 ring-blue-600 ring-offset-2 scale-105 z-10 shadow-lg' : ''
                                            }`}
                                        >
                                            {idx + 1}
                                        </button>
                                    );
                                })}
                            </div>

                            {/* Legend */}
                            <div className="pt-3 border-t border-slate-100 text-xs space-y-2.5 text-slate-700 font-bold">
                                <div className="flex items-center gap-2">
                                    <div className="size-4 rounded-lg bg-blue-600 shrink-0 shadow-xs" />
                                    <span>Sudah Dijawab ({answeredCount})</span>
                                </div>
                                <div className="flex items-center gap-2">
                                    <div className="size-4 rounded-lg bg-amber-400 border border-amber-500 shrink-0 shadow-xs" />
                                    <span>Ragu-Ragu ({flaggedCount})</span>
                                </div>
                                <div className="flex items-center gap-2">
                                    <div className="size-4 rounded-lg bg-slate-200 border border-slate-300 shrink-0" />
                                    <span>Belum Dijawab ({questions.length - answeredCount})</span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </main>

            {/* Confirm Submit Dialog */}
            <Dialog open={isFinishDialogOpen} onOpenChange={setIsFinishDialogOpen}>
                <DialogContent className="sm:max-w-[420px]">
                    <DialogHeader>
                        <DialogTitle className="flex items-center gap-2 text-slate-900 text-xl font-bold">
                            <AlertTriangle className="size-6 text-amber-500" />
                            Konfirmasi Selesai Ujian
                        </DialogTitle>
                        <DialogDescription className="pt-2 text-slate-600 text-sm">
                            Apakah Anda yakin ingin mengakhiri ujian ini sekarang? Setelah dikumpulkan, jawaban tidak dapat diubah lagi.
                        </DialogDescription>
                    </DialogHeader>

                    <div className="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-xs space-y-2 my-2">
                        <div className="flex justify-between">
                            <span>Total Soal:</span>
                            <span className="font-bold text-slate-900">{questions.length}</span>
                        </div>
                        <div className="flex justify-between text-blue-700 font-bold">
                            <span>Sudah Dijawab:</span>
                            <span className="font-extrabold">{answeredCount}</span>
                        </div>
                        {flaggedCount > 0 && (
                            <div className="flex justify-between text-amber-700 font-bold">
                                <span>Masih Ragu-Ragu:</span>
                                <span className="font-extrabold">{flaggedCount}</span>
                            </div>
                        )}
                        <div className="flex justify-between text-slate-500">
                            <span>Belum Dijawab:</span>
                            <span className="font-bold">{questions.length - answeredCount}</span>
                        </div>
                    </div>

                    <DialogFooter className="gap-2 sm:gap-0 pt-2">
                        <Button variant="outline" onClick={() => setIsFinishDialogOpen(false)} className="rounded-xl">
                            Kembali Kerjakan
                        </Button>
                        <Button
                            onClick={handleConfirmFinish}
                            disabled={isSubmitting}
                            className="bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md shadow-emerald-600/20"
                        >
                            Ya, Selesaikan Ujian
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    );
}
