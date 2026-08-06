import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Trophy, CheckCircle, XCircle, ArrowLeft, Award, BookOpen, AlertTriangle } from 'lucide-react';

interface QuestionDetail {
    question_id: number;
    question_text: string;
    option_a: string;
    option_b: string;
    option_c: string;
    option_d: string;
    option_e: string;
    correct_option: 'a' | 'b' | 'c' | 'd' | 'e';
    selected_option: 'a' | 'b' | 'c' | 'd' | 'e' | null;
    is_correct: boolean;
}

interface Props {
    exam: {
        id: number;
        title: string;
        subject: string;
        duration_minutes: number;
    };
    session: {
        started_at: string;
        finished_at: string;
        score: number;
        status: string;
    };
    total_questions: number;
    correct_count: number;
    incorrect_count: number;
    details: QuestionDetail[];
}

export default function StudentExamResult({
    exam,
    session,
    total_questions,
    correct_count,
    incorrect_count,
    details,
}: Props) {
    const score = session.score || 0;

    const getScoreInfo = (s: number) => {
        if (s >= 85) {
            return {
                label: 'Sangat Baik / Lulus Sempurna',
                badgeColor: 'bg-emerald-600 text-white',
                textColor: 'text-emerald-600',
                borderColor: 'border-emerald-300',
                cardBg: 'bg-gradient-to-br from-emerald-50 via-white to-emerald-50/30',
                icon: <Trophy className="size-10 text-emerald-600" />,
            };
        }
        if (s >= 70) {
            return {
                label: 'Baik / Lulus',
                badgeColor: 'bg-blue-600 text-white',
                textColor: 'text-blue-600',
                borderColor: 'border-blue-300',
                cardBg: 'bg-gradient-to-br from-blue-50 via-white to-blue-50/30',
                icon: <Award className="size-10 text-blue-600" />,
            };
        }
        if (s >= 50) {
            return {
                label: 'Cukup / Perlu Belajar Lagi',
                badgeColor: 'bg-amber-500 text-white',
                textColor: 'text-amber-600',
                borderColor: 'border-amber-300',
                cardBg: 'bg-gradient-to-br from-amber-50 via-white to-amber-50/30',
                icon: <Award className="size-10 text-amber-600" />,
            };
        }
        if (s > 0) {
            return {
                label: 'Kurang / Belum Lulus',
                badgeColor: 'bg-orange-600 text-white',
                textColor: 'text-orange-600',
                borderColor: 'border-orange-300',
                cardBg: 'bg-gradient-to-br from-orange-50 via-white to-orange-50/30',
                icon: <AlertTriangle className="size-10 text-orange-600" />,
            };
        }
        return {
            label: 'Sangat Kurang / Tidak Ada Jawaban Benar',
            badgeColor: 'bg-rose-600 text-white',
            textColor: 'text-rose-600',
            borderColor: 'border-rose-300',
            cardBg: 'bg-gradient-to-br from-rose-50 via-white to-rose-50/30',
            icon: <XCircle className="size-10 text-rose-600" />,
        };
    };

    const scoreInfo = getScoreInfo(score);

    return (
        <AppLayout
            breadcrumbs={[
                { title: 'Daftar Ujian', href: '/dashboard' },
                { title: `Hasil - ${exam.title}`, href: `/student/exams/${exam.id}/result` },
            ]}
        >
            <Head title={`Hasil Ujian - ${exam.title}`} />

            <div className="flex flex-col gap-6 p-6 max-w-5xl mx-auto w-full">
                {/* Back to dashboard button */}
                <div className="flex items-center justify-between">
                    <Link href="/dashboard">
                        <Button variant="outline" className="gap-2 border-slate-200 text-slate-700 font-extrabold rounded-2xl h-11 px-5">
                            <ArrowLeft className="size-5" />
                            Kembali ke Dashboard
                        </Button>
                    </Link>

                    <Badge variant="outline" className="text-xs bg-blue-50 text-blue-700 border-blue-200 font-extrabold px-3 py-1">
                        Mapel: {exam.subject}
                    </Badge>
                </div>

                {/* Main Score Hero Card */}
                <Card
                    className={`border shadow-lg text-center py-8 px-6 relative overflow-hidden rounded-3xl ${scoreInfo.cardBg} ${scoreInfo.borderColor}`}
                >
                    <CardContent className="space-y-4">
                        <div className="size-20 rounded-3xl mx-auto flex items-center justify-center bg-white shadow-md border border-slate-100">
                            {scoreInfo.icon}
                        </div>

                        <div>
                            <h1 className="text-2xl sm:text-3xl font-black tracking-tight text-slate-900">{exam.title}</h1>
                            <p className="text-slate-500 text-xs sm:text-sm font-semibold mt-1">
                                Waktu Selesai: {session.finished_at || '-'}
                            </p>
                        </div>

                        <div className="inline-block py-3 px-10 rounded-3xl bg-white border border-slate-200 shadow-md">
                            <span className="text-xs uppercase font-black tracking-wider text-slate-500 block mb-1">
                                Nilai Akhir Anda
                            </span>
                            <span className={`text-6xl font-black ${scoreInfo.textColor}`}>
                                {score}
                            </span>
                        </div>

                        <div>
                            <Badge className={`${scoreInfo.badgeColor} px-5 py-1.5 text-sm font-black rounded-xl shadow-xs`}>
                                {scoreInfo.label}
                            </Badge>
                        </div>
                    </CardContent>
                </Card>

                {/* Score Stats Grid */}
                <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <Card className="border border-slate-200/80 shadow-md shadow-blue-950/5 bg-white rounded-3xl">
                        <CardHeader className="flex flex-row items-center justify-between pb-2">
                            <CardTitle className="text-xs font-black uppercase tracking-wider text-slate-500">Total Soal</CardTitle>
                            <div className="p-2.5 bg-blue-50 text-blue-600 rounded-2xl border border-blue-100">
                                <BookOpen className="size-5" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="text-3xl font-black text-slate-900">{total_questions}</div>
                        </CardContent>
                    </Card>

                    <Card className="border border-slate-200/80 shadow-md shadow-blue-950/5 bg-white rounded-3xl">
                        <CardHeader className="flex flex-row items-center justify-between pb-2">
                            <CardTitle className="text-xs font-black uppercase tracking-wider text-slate-500">Jawaban Benar</CardTitle>
                            <div className="p-2.5 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-100">
                                <CheckCircle className="size-5" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="text-3xl font-black text-emerald-600">{correct_count}</div>
                        </CardContent>
                    </Card>

                    <Card className="border border-slate-200/80 shadow-md shadow-blue-950/5 bg-white rounded-3xl">
                        <CardHeader className="flex flex-row items-center justify-between pb-2">
                            <CardTitle className="text-xs font-black uppercase tracking-wider text-slate-500">Jawaban Salah / Kosong</CardTitle>
                            <div className="p-2.5 bg-rose-50 text-rose-600 rounded-2xl border border-rose-100">
                                <XCircle className="size-5" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="text-3xl font-black text-rose-600">{incorrect_count}</div>
                        </CardContent>
                    </Card>
                </div>

                {/* Detailed Answers Discussion / Review */}
                <Card className="border border-slate-200/80 shadow-md shadow-blue-950/5 bg-white rounded-3xl overflow-hidden">
                    <CardHeader className="border-b border-slate-100 pb-4">
                        <CardTitle className="text-xl font-black text-slate-900">Pembahasan & Rincian Jawaban</CardTitle>
                        <CardDescription className="text-slate-500 font-medium text-xs sm:text-sm">
                            Evaluasi jawaban Anda dengan kunci jawaban yang benar di bawah ini.
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="p-6 space-y-6">
                        {details.map((q, idx) => (
                            <div
                                key={q.question_id}
                                className={`p-6 rounded-3xl border-2 transition-all ${
                                    q.is_correct
                                        ? 'bg-emerald-50/40 border-emerald-200'
                                        : 'bg-rose-50/40 border-rose-200'
                                }`}
                            >
                                <div className="flex items-center justify-between gap-4 mb-4">
                                    <div className="flex items-center gap-2.5">
                                        <Badge
                                            className={
                                                q.is_correct
                                                    ? 'bg-emerald-600 text-white font-black px-3 py-1 text-xs rounded-xl'
                                                    : 'bg-rose-600 text-white font-black px-3 py-1 text-xs rounded-xl'
                                            }
                                        >
                                            Soal Nomor {idx + 1}
                                        </Badge>
                                        {q.is_correct ? (
                                            <span className="text-xs font-black text-emerald-600 flex items-center gap-1.5 bg-emerald-100/80 px-3 py-1 rounded-xl">
                                                <CheckCircle className="size-4" /> Benar
                                            </span>
                                        ) : (
                                            <span className="text-xs font-black text-rose-600 flex items-center gap-1.5 bg-rose-100/80 px-3 py-1 rounded-xl">
                                                <XCircle className="size-4" /> Salah / Kosong
                                            </span>
                                        )}
                                    </div>
                                </div>

                                <p className="font-extrabold text-slate-900 text-base sm:text-lg mb-5 whitespace-pre-line leading-relaxed">
                                    {q.question_text}
                                </p>

                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                    {(['a', 'b', 'c', 'd', 'e'] as const).map(optKey => {
                                        const optionText = q[`option_${optKey}`];
                                        const isUserSelected = q.selected_option === optKey;
                                        const isCorrectOpt = q.correct_option === optKey;

                                        let styleClasses = 'bg-white border-slate-200 text-slate-700';
                                        if (isCorrectOpt) {
                                            styleClasses = 'bg-emerald-100/90 border-2 border-emerald-400 text-emerald-950 font-black shadow-xs';
                                        } else if (isUserSelected && !isCorrectOpt) {
                                            styleClasses = 'bg-rose-100/90 border-2 border-rose-300 text-rose-950 font-bold line-through';
                                        }

                                        return (
                                            <div
                                                key={optKey}
                                                className={`p-3.5 rounded-2xl border flex items-start gap-3 ${styleClasses}`}
                                            >
                                                <span
                                                    className={`size-6 rounded-xl text-xs font-black flex items-center justify-center shrink-0 uppercase ${
                                                        isCorrectOpt
                                                            ? 'bg-emerald-600 text-white'
                                                            : isUserSelected
                                                            ? 'bg-rose-600 text-white'
                                                            : 'bg-slate-100 text-slate-700'
                                                    }`}
                                                >
                                                    {optKey}
                                                </span>
                                                <span className="flex-1 font-semibold">{optionText}</span>
                                                {isCorrectOpt && (
                                                    <span className="text-xs text-emerald-800 font-extrabold shrink-0 bg-emerald-200/80 px-2 py-0.5 rounded-lg">
                                                        Kunci
                                                    </span>
                                                )}
                                                {isUserSelected && !isCorrectOpt && (
                                                    <span className="text-xs text-rose-700 font-extrabold shrink-0 bg-rose-200/80 px-2 py-0.5 rounded-lg">
                                                        Jawaban Anda
                                                    </span>
                                                )}
                                            </div>
                                        );
                                    })}
                                </div>
                            </div>
                        ))}
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
