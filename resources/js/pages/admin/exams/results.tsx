import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import {
    ArrowLeft, Users, Trophy, Award, BarChart3, CheckCircle,
    Clock, CheckCircle2, XCircle, RotateCcw, Trash2, AlertTriangle, X
} from 'lucide-react';

interface SessionItem {
    id: number;
    user_name: string;
    user_email: string;
    started_at: string;
    finished_at: string | null;
    status: 'in_progress' | 'completed';
    score: number | null;
    total_answered: number;
    total_unanswered: number;
    total_questions: number;
}

interface Exam {
    id: number;
    title: string;
    subject: string;
    duration_minutes: number;
    total_questions: number;
}

interface Props {
    exam: Exam;
    sessions: SessionItem[];
}

export default function AdminExamResults({ exam, sessions }: Props) {
    const [sessionToReset, setSessionToReset] = useState<SessionItem | null>(null);
    const [sessionToDelete, setSessionToDelete] = useState<SessionItem | null>(null);
    const [isProcessing, setIsProcessing] = useState(false);

    const completedSessions = sessions.filter(s => s.status === 'completed' && s.score !== null);
    const totalParticipants = sessions.length;

    const scores = completedSessions.map(s => s.score || 0);
    const avgScore = scores.length > 0 ? (scores.reduce((a, b) => a + b, 0) / scores.length).toFixed(1) : '0';
    const maxScore = scores.length > 0 ? Math.max(...scores) : 0;
    const minScore = scores.length > 0 ? Math.min(...scores) : 0;

    const handleReset = () => {
        if (!sessionToReset) return;
        setIsProcessing(true);
        router.post(`/admin/exam-sessions/${sessionToReset.id}/reset`, {}, {
            onFinish: () => {
                setIsProcessing(false);
                setSessionToReset(null);
            }
        });
    };

    const handleDelete = () => {
        if (!sessionToDelete) return;
        setIsProcessing(true);
        router.delete(`/admin/exam-sessions/${sessionToDelete.id}`, {
            onFinish: () => {
                setIsProcessing(false);
                setSessionToDelete(null);
            }
        });
    };

    return (
        <AppLayout
            breadcrumbs={[
                { title: 'Kelola Ujian', href: '/admin/exams' },
                { title: exam.title, href: `/admin/exams/${exam.id}` },
                { title: 'Hasil Ujian', href: `/admin/exams/${exam.id}/results` },
            ]}
        >
            <Head title={`Hasil Ujian - ${exam.title}`} />

            <div className="flex flex-col gap-6 p-6">
                <div className="flex items-center gap-3">
                    <Link href="/admin/exams">
                        <Button variant="outline" size="icon" className="size-10 rounded-2xl border-slate-200">
                            <ArrowLeft className="size-5" />
                        </Button>
                    </Link>
                    <div>
                        <h1 className="text-2xl font-black tracking-tight text-slate-900">Hasil & Rekap Nilai Siswa</h1>
                        <p className="text-blue-600 font-extrabold text-sm">
                            {exam.title} • {exam.subject} ({exam.total_questions || 0} Soal)
                        </p>
                    </div>
                </div>

                {/* Stats Grid */}
                <div className="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <Card className="border border-slate-200/80 shadow-md shadow-blue-950/5 bg-white rounded-3xl">
                        <CardHeader className="flex flex-row items-center justify-between pb-2">
                            <CardTitle className="text-xs font-black uppercase tracking-wider text-slate-500">Total Peserta</CardTitle>
                            <div className="p-2.5 bg-blue-50 text-blue-600 rounded-2xl border border-blue-100">
                                <Users className="size-5" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="text-3xl font-black text-slate-900">{totalParticipants}</div>
                        </CardContent>
                    </Card>

                    <Card className="border border-slate-200/80 shadow-md shadow-blue-950/5 bg-white rounded-3xl">
                        <CardHeader className="flex flex-row items-center justify-between pb-2">
                            <CardTitle className="text-xs font-black uppercase tracking-wider text-slate-500">Rata-Rata Nilai</CardTitle>
                            <div className="p-2.5 bg-blue-50 text-blue-600 rounded-2xl border border-blue-100">
                                <BarChart3 className="size-5" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="text-3xl font-black text-blue-600">{avgScore}</div>
                        </CardContent>
                    </Card>

                    <Card className="border border-slate-200/80 shadow-md shadow-blue-950/5 bg-white rounded-3xl">
                        <CardHeader className="flex flex-row items-center justify-between pb-2">
                            <CardTitle className="text-xs font-black uppercase tracking-wider text-slate-500">Nilai Tertinggi</CardTitle>
                            <div className="p-2.5 bg-amber-50 text-amber-600 rounded-2xl border border-amber-100">
                                <Trophy className="size-5" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="text-3xl font-black text-amber-600">{maxScore}</div>
                        </CardContent>
                    </Card>

                    <Card className="border border-slate-200/80 shadow-md shadow-blue-950/5 bg-white rounded-3xl">
                        <CardHeader className="flex flex-row items-center justify-between pb-2">
                            <CardTitle className="text-xs font-black uppercase tracking-wider text-slate-500">Nilai Terendah</CardTitle>
                            <div className="p-2.5 bg-slate-100 text-slate-600 rounded-2xl border border-slate-200">
                                <Award className="size-5" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="text-3xl font-black text-slate-700">{minScore}</div>
                        </CardContent>
                    </Card>
                </div>

                {/* Sessions Table */}
                <Card className="border border-slate-200/80 shadow-md shadow-blue-950/5 bg-white rounded-3xl overflow-hidden">
                    <CardHeader className="border-b border-slate-100 pb-4">
                        <CardTitle className="text-xl font-black text-slate-900">Daftar Pengerjaan Siswa</CardTitle>
                        <CardDescription className="text-slate-500 text-xs sm:text-sm font-medium">
                            Rincian pengerjaan, status sesi, nilai akhir, serta opsi pengelolaan (Reset / Hapus data pengerjaan).
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="p-0 sm:p-6">
                        {sessions.length === 0 ? (
                            <div className="text-center py-16 text-slate-500">
                                <Users className="size-14 mx-auto stroke-1 opacity-40 mb-3 text-blue-600" />
                                <p className="font-extrabold text-lg text-slate-900">Belum Ada Peserta Mengikuti Ujian Ini</p>
                            </div>
                        ) : (
                            <div className="overflow-x-auto">
                                <table className="w-full text-sm text-left">
                                    <thead className="bg-slate-50 text-slate-600 text-xs uppercase font-black border-b border-slate-200">
                                        <tr>
                                            <th className="px-5 py-4">Nama Siswa</th>
                                            <th className="px-4 py-4">Waktu Mulai</th>
                                            <th className="px-4 py-4">Waktu Selesai</th>
                                            <th className="px-4 py-4">Pengerjaan Soal</th>
                                            <th className="px-4 py-4">Status Sesi</th>
                                            <th className="px-4 py-4 text-center">Nilai Akhir</th>
                                            <th className="px-5 py-4 text-center">Aksi (CRUD)</th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-slate-100">
                                        {sessions.map(s => (
                                            <tr key={s.id} className="hover:bg-blue-50/40 transition-colors">
                                                <td className="px-5 py-4 font-medium">
                                                    <div className="font-black text-slate-900 text-base">{s.user_name}</div>
                                                    <div className="text-xs text-slate-500 font-semibold">{s.user_email}</div>
                                                </td>
                                                <td className="px-4 py-4 text-xs font-semibold text-slate-600 whitespace-nowrap">
                                                    {s.started_at}
                                                </td>
                                                <td className="px-4 py-4 text-xs font-semibold text-slate-600 whitespace-nowrap">
                                                    {s.finished_at || '-'}
                                                </td>
                                                <td className="px-4 py-4 whitespace-nowrap">
                                                    <div className="flex flex-wrap items-center gap-1.5">
                                                        <Badge variant="outline" className="bg-emerald-50 border-emerald-200 text-emerald-800 font-black px-2.5 py-0.5 text-xs gap-1">
                                                            <CheckCircle2 className="size-3 text-emerald-600" />
                                                            {s.total_answered} Terjawab
                                                        </Badge>

                                                        {s.total_unanswered > 0 ? (
                                                            <Badge variant="outline" className="bg-rose-50 border-rose-200 text-rose-700 font-black px-2.5 py-0.5 text-xs gap-1">
                                                                <XCircle className="size-3 text-rose-500" />
                                                                {s.total_unanswered} Kosong
                                                            </Badge>
                                                        ) : (
                                                            <Badge variant="outline" className="bg-slate-50 border-slate-200 text-slate-600 font-bold px-2 py-0.5 text-xs">
                                                                0 Kosong
                                                            </Badge>
                                                        )}
                                                    </div>
                                                </td>
                                                <td className="px-4 py-4 whitespace-nowrap">
                                                    {s.status === 'completed' ? (
                                                        <Badge className="bg-emerald-600 text-white font-extrabold gap-1 text-xs px-2.5 py-0.5">
                                                            <CheckCircle className="size-3.5" /> Selesai
                                                        </Badge>
                                                    ) : (
                                                        <Badge className="bg-amber-500 text-white font-extrabold gap-1 text-xs px-2.5 py-0.5">
                                                            <Clock className="size-3.5" /> Sedang Dikerjakan
                                                        </Badge>
                                                    )}
                                                </td>
                                                <td className="px-4 py-4 text-center whitespace-nowrap font-black text-2xl">
                                                    {s.score !== null ? (
                                                        <span className={s.score >= 70 ? 'text-emerald-600' : 'text-rose-600'}>
                                                            {s.score}
                                                        </span>
                                                    ) : (
                                                        <span className="text-slate-400 text-sm font-normal">-</span>
                                                    )}
                                                </td>
                                                <td className="px-5 py-4 text-center whitespace-nowrap">
                                                    <div className="flex items-center justify-center gap-2">
                                                        <Button
                                                            size="sm"
                                                            variant="outline"
                                                            onClick={() => setSessionToReset(s)}
                                                            className="h-8 text-xs font-extrabold border-amber-300 bg-amber-50 text-amber-800 hover:bg-amber-100 hover:border-amber-400 rounded-xl gap-1.5"
                                                            title="Reset pengerjaan agar siswa bisa ujian ulang"
                                                        >
                                                            <RotateCcw className="size-3.5" />
                                                            Reset Ujian
                                                        </Button>

                                                        <Button
                                                            size="sm"
                                                            variant="outline"
                                                            onClick={() => setSessionToDelete(s)}
                                                            className="h-8 text-xs font-extrabold border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 hover:border-rose-300 rounded-xl gap-1.5"
                                                            title="Hapus permanen data pengerjaan ini"
                                                        >
                                                            <Trash2 className="size-3.5" />
                                                            Hapus
                                                        </Button>
                                                    </div>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        )}
                    </CardContent>
                </Card>
            </div>

            {/* Modal Confirm Reset */}
            {sessionToReset && (
                <div className="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
                    <div className="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl border border-slate-100 relative animate-in fade-in zoom-in-95 duration-150">
                        <button
                            onClick={() => setSessionToReset(null)}
                            className="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-600 rounded-full hover:bg-slate-100 transition-colors"
                        >
                            <X className="size-5" />
                        </button>
                        <div className="flex items-center gap-3 text-amber-600 mb-4">
                            <div className="p-3 bg-amber-100 rounded-2xl">
                                <RotateCcw className="size-6" />
                            </div>
                            <h3 className="text-lg font-black text-slate-900">Reset Pengerjaan Ujian?</h3>
                        </div>
                        <p className="text-sm text-slate-600 leading-relaxed mb-6 font-medium">
                            Apakah Anda yakin ingin me-reset pengerjaan ujian untuk <span className="font-extrabold text-slate-900">{sessionToReset.user_name}</span>?
                            <br /><br />
                            <span className="text-xs text-amber-700 bg-amber-50 p-2.5 rounded-xl block border border-amber-200 font-semibold">
                                ℹ️ Seluruh jawaban siswa pada sesi ini akan dihapus. Siswa akan diizinkan untuk memulai ujian dari awal kembali.
                            </span>
                        </p>
                        <div className="flex justify-end gap-3">
                            <Button
                                variant="outline"
                                onClick={() => setSessionToReset(null)}
                                className="rounded-xl font-bold border-slate-200"
                                disabled={isProcessing}
                            >
                                Batal
                            </Button>
                            <Button
                                onClick={handleReset}
                                disabled={isProcessing}
                                className="bg-amber-600 hover:bg-amber-700 text-white font-extrabold rounded-xl"
                            >
                                {isProcessing ? 'Memproses...' : 'Ya, Reset Ujian'}
                            </Button>
                        </div>
                    </div>
                </div>
            )}

            {/* Modal Confirm Delete */}
            {sessionToDelete && (
                <div className="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
                    <div className="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl border border-slate-100 relative animate-in fade-in zoom-in-95 duration-150">
                        <button
                            onClick={() => setSessionToDelete(null)}
                            className="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-600 rounded-full hover:bg-slate-100 transition-colors"
                        >
                            <X className="size-5" />
                        </button>
                        <div className="flex items-center gap-3 text-rose-600 mb-4">
                            <div className="p-3 bg-rose-100 rounded-2xl">
                                <AlertTriangle className="size-6" />
                            </div>
                            <h3 className="text-lg font-black text-slate-900">Hapus Data Hasil Ujian?</h3>
                        </div>
                        <p className="text-sm text-slate-600 leading-relaxed mb-6 font-medium">
                            Apakah Anda yakin ingin menghapus data hasil pengerjaan <span className="font-extrabold text-slate-900">{sessionToDelete.user_name}</span>?
                            <br /><br />
                            <span className="text-xs text-rose-700 bg-rose-50 p-2.5 rounded-xl block border border-rose-200 font-semibold">
                                ⚠️ Tindakan ini tidak dapat dibatalkan. Data nilai dan jawaban siswa ini akan dihapus permanen dari sistem.
                            </span>
                        </p>
                        <div className="flex justify-end gap-3">
                            <Button
                                variant="outline"
                                onClick={() => setSessionToDelete(null)}
                                className="rounded-xl font-bold border-slate-200"
                                disabled={isProcessing}
                            >
                                Batal
                            </Button>
                            <Button
                                onClick={handleDelete}
                                disabled={isProcessing}
                                className="bg-rose-600 hover:bg-rose-700 text-white font-extrabold rounded-xl"
                            >
                                {isProcessing ? 'Memproses...' : 'Ya, Hapus Data'}
                            </Button>
                        </div>
                    </div>
                </div>
            )}
        </AppLayout>
    );
}
