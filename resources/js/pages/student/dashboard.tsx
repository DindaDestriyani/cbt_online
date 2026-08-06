import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { BookOpen, Clock, FileQuestion, PlayCircle, CheckCircle2, ArrowRight, Award, ShieldCheck } from 'lucide-react';

interface ExamItem {
    id: number;
    title: string;
    subject: string;
    duration_minutes: number;
    questions_count: number;
    session_status: 'in_progress' | 'completed' | null;
    score: number | null;
}

interface Props {
    exams: ExamItem[];
}

export default function StudentDashboard({ exams }: Props) {
    return (
        <AppLayout breadcrumbs={[{ title: 'Daftar Ujian', href: '/dashboard' }]}>
            <Head title="Dashboard Siswa - CBT Online" />

            <div className="flex flex-col gap-6 p-6">
                {/* Welcome Banner */}
                <div className="rounded-3xl bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700 p-6 sm:p-8 text-white shadow-xl shadow-blue-600/20 relative overflow-hidden">
                    <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 relative z-10">
                        <div className="flex items-start gap-4">
                            <div className="p-3.5 bg-white/20 rounded-2xl backdrop-blur-md shrink-0 border border-white/20">
                                <BookOpen className="size-8 text-white" />
                            </div>
                            <div>
                                <div className="flex items-center gap-2 mb-1">
                                    <h1 className="text-2xl sm:text-3xl font-black tracking-tight">Portal Ujian Siswa</h1>
                                    <Badge className="bg-white/20 text-white border border-white/30 text-[10px] uppercase font-bold px-2 py-0.5">
                                        CBT System
                                    </Badge>
                                </div>
                                <p className="text-blue-100 text-xs sm:text-sm font-medium max-w-xl">
                                    Pilih paket ujian yang tersedia di bawah ini untuk memulai pengerjaan. Nilai dihitung secara otomatis.
                                </p>
                            </div>
                        </div>

                        <div className="flex items-center gap-2 bg-white/15 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-white/20 text-xs font-bold text-white">
                            <ShieldCheck className="size-4 text-emerald-300" />
                            <span>Koneksi Aman & Terenkripsi</span>
                        </div>
                    </div>
                </div>

                {/* Available Exams Section */}
                <div className="space-y-4">
                    <div className="flex items-center justify-between">
                        <h2 className="text-xl font-black tracking-tight text-slate-900 dark:text-white">Daftar Ujian Aktif</h2>
                        <span className="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1 rounded-full">{exams.length} Ujian Tersedia</span>
                    </div>

                    {exams.length === 0 ? (
                        <Card className="border border-slate-200 shadow-md shadow-blue-950/5 text-center py-16 bg-white rounded-3xl">
                            <CardContent>
                                <BookOpen className="size-14 mx-auto stroke-1 text-blue-600 opacity-40 mb-3" />
                                <p className="font-extrabold text-lg text-slate-900">Belum Ada Ujian Aktif</p>
                                <p className="text-sm text-slate-500">Saat ini belum ada ujian yang dibuka oleh Pengajar. Silakan periksa kembali nanti.</p>
                            </CardContent>
                        </Card>
                    ) : (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                            {exams.map(exam => {
                                const isCompleted = exam.session_status === 'completed';
                                const isInProgress = exam.session_status === 'in_progress';

                                return (
                                    <Card
                                        key={exam.id}
                                        className={`border shadow-md shadow-blue-950/5 flex flex-col justify-between transition-all rounded-3xl overflow-hidden hover:-translate-y-1 ${
                                            isCompleted
                                                ? 'bg-emerald-50/50 border-emerald-200'
                                                : isInProgress
                                                ? 'bg-amber-50/50 border-amber-200'
                                                : 'bg-white border-slate-200 hover:border-blue-400'
                                        }`}
                                    >
                                        <CardHeader className="pb-3">
                                            <div className="flex items-start justify-between gap-2 mb-2">
                                                <Badge variant="outline" className="text-xs font-bold bg-blue-50 text-blue-700 border-blue-200 px-2.5 py-0.5">
                                                    {exam.subject}
                                                </Badge>
                                                {isCompleted ? (
                                                    <Badge className="bg-emerald-600 text-white font-bold gap-1 px-2.5 py-0.5 text-xs shadow-xs">
                                                        <CheckCircle2 className="size-3.5" /> Selesai
                                                    </Badge>
                                                ) : isInProgress ? (
                                                    <Badge className="bg-amber-500 text-white font-bold gap-1 px-2.5 py-0.5 text-xs shadow-xs">
                                                        <Clock className="size-3.5 animate-spin" /> Lanjutkan
                                                    </Badge>
                                                ) : (
                                                    <Badge className="bg-blue-600 text-white font-bold px-2.5 py-0.5 text-xs shadow-xs">
                                                        Tersedia
                                                    </Badge>
                                                )}
                                            </div>
                                            <CardTitle className="text-lg font-black text-slate-900 line-clamp-2 leading-snug">{exam.title}</CardTitle>
                                        </CardHeader>

                                        <CardContent className="space-y-4 pt-0">
                                            <div className="grid grid-cols-2 gap-2 text-xs font-semibold text-slate-600 bg-slate-50 p-3 rounded-2xl border border-slate-100">
                                                <div className="flex items-center gap-1.5">
                                                    <Clock className="size-3.5 text-blue-600 shrink-0" />
                                                    <span>Durasi: <strong>{exam.duration_minutes}m</strong></span>
                                                </div>
                                                <div className="flex items-center gap-1.5">
                                                    <FileQuestion className="size-3.5 text-blue-600 shrink-0" />
                                                    <span>Jumlah: <strong>{exam.questions_count} Soal</strong></span>
                                                </div>
                                            </div>

                                            {isCompleted && exam.score !== null && (
                                                <div className="flex items-center justify-between p-3.5 rounded-2xl bg-emerald-100/80 border border-emerald-300 shadow-xs">
                                                    <span className="text-xs font-bold text-emerald-900 flex items-center gap-1.5">
                                                        <Award className="size-4 text-emerald-600" /> Nilai Akhir:
                                                    </span>
                                                    <span className="text-2xl font-black text-emerald-700">{exam.score}</span>
                                                </div>
                                            )}

                                            <div className="pt-1">
                                                {isCompleted ? (
                                                    <Link href={`/student/exams/${exam.id}/result`}>
                                                        <Button variant="outline" className="w-full gap-2 border-emerald-600 text-emerald-800 hover:bg-emerald-50 font-bold rounded-2xl h-11">
                                                            Lihat Pembahasan & Nilai <ArrowRight className="size-4" />
                                                        </Button>
                                                    </Link>
                                                ) : isInProgress ? (
                                                    <Link href={`/student/exams/${exam.id}/room`}>
                                                        <Button className="w-full gap-2 bg-amber-500 hover:bg-amber-600 text-white font-bold shadow-md shadow-amber-500/20 rounded-2xl h-11">
                                                            <PlayCircle className="size-4" /> Lanjutkan Ujian
                                                        </Button>
                                                    </Link>
                                                ) : (
                                                    <Link href={`/student/exams/${exam.id}/start`}>
                                                        <Button className="w-full gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-lg shadow-blue-600/20 rounded-2xl h-11">
                                                            <PlayCircle className="size-4" /> Mulai Kerjakan
                                                        </Button>
                                                    </Link>
                                                )}
                                            </div>
                                        </CardContent>
                                    </Card>
                                );
                            })}
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
