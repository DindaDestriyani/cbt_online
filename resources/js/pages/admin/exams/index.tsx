import { useState } from 'react';
import { Head, Link, useForm, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Badge } from '@/components/ui/badge';
import { Plus, BookOpen, Clock, FileQuestion, Users, Trash2, Edit, CheckCircle2, Sparkles } from 'lucide-react';

interface Exam {
    id: number;
    title: string;
    subject: string;
    duration_minutes: number;
    is_active: boolean;
    questions_count: number;
    sessions_count: number;
    created_at: string;
}

interface Props {
    exams: Exam[];
}

export default function AdminExamsIndex({ exams }: Props) {
    const [isCreateOpen, setIsCreateOpen] = useState(false);
    const [editingExam, setEditingExam] = useState<Exam | null>(null);

    const form = useForm({
        title: '',
        subject: '',
        duration_minutes: 30 as number | string,
        is_active: true,
    });

    const editForm = useForm({
        title: '',
        subject: '',
        duration_minutes: 30 as number | string,
        is_active: true,
    });

    const handleCreateSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        const parsedDuration = Math.max(1, parseInt(String(form.data.duration_minutes)) || 30);
        
        router.post('/admin/exams', {
            title: form.data.title,
            subject: form.data.subject,
            duration_minutes: parsedDuration,
            is_active: form.data.is_active,
        }, {
            onSuccess: () => {
                setIsCreateOpen(false);
                form.reset();
            },
            onError: (errors) => {
                form.setErrors(errors);
            },
        });
    };

    const handleEditOpen = (exam: Exam) => {
        setEditingExam(exam);
        editForm.setData({
            title: exam.title,
            subject: exam.subject,
            duration_minutes: exam.duration_minutes,
            is_active: exam.is_active,
        });
    };

    const handleEditSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (!editingExam) return;
        const parsedDuration = Math.max(1, parseInt(String(editForm.data.duration_minutes)) || 30);

        router.put(`/admin/exams/${editingExam.id}`, {
            title: editForm.data.title,
            subject: editForm.data.subject,
            duration_minutes: parsedDuration,
            is_active: editForm.data.is_active,
        }, {
            onSuccess: () => {
                setEditingExam(null);
            },
            onError: (errors) => {
                editForm.setErrors(errors);
            },
        });
    };

    const handleDelete = (id: number) => {
        if (confirm('Apakah Anda yakin ingin menghapus ujian ini? Semua soal dan data ujian siswa juga akan terhapus.')) {
            router.delete(`/admin/exams/${id}`);
        }
    };

    const totalExams = exams.length;
    const totalQuestions = exams.reduce((acc, curr) => acc + curr.questions_count, 0);
    const activeExams = exams.filter(e => e.is_active).length;

    return (
        <AppLayout breadcrumbs={[{ title: 'Kelola Ujian', href: '/admin/exams' }]}>
            <Head title="Kelola Ujian - Admin" />

            <div className="flex flex-col gap-6 p-6">
                {/* Header Title & Create Button */}
                <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700 p-6 sm:p-8 rounded-3xl text-white shadow-xl shadow-blue-600/15">
                    <div>
                        <div className="flex items-center gap-2 mb-1">
                            <span className="p-1.5 bg-white/20 rounded-lg backdrop-blur-md">
                                <Sparkles className="size-4 text-white" />
                            </span>
                            <span className="text-xs font-bold uppercase tracking-wider text-blue-100">Panel Admin CBT</span>
                        </div>
                        <h1 className="text-2xl sm:text-3xl font-black tracking-tight">Kelola Ujian Online</h1>
                        <p className="text-blue-100 text-xs sm:text-sm mt-1 max-w-xl font-medium">
                            Buat paket ujian baru, atur durasi, dan hubungkan dengan Bank Soal master dengan mudah.
                        </p>
                    </div>

                    <Dialog open={isCreateOpen} onOpenChange={setIsCreateOpen}>
                        <DialogTrigger asChild>
                            <Button className="gap-2 bg-white text-blue-700 hover:bg-blue-50 font-black shadow-lg shadow-blue-900/20 rounded-2xl h-11 px-6 text-sm">
                                <Plus className="size-5" />
                                Buat Ujian Baru
                            </Button>
                        </DialogTrigger>
                        <DialogContent className="sm:max-w-[480px]">
                            <form onSubmit={handleCreateSubmit}>
                                <DialogHeader>
                                    <DialogTitle className="text-xl font-extrabold">Buat Ujian Baru</DialogTitle>
                                    <DialogDescription>
                                        Isi detail ujian di bawah ini. Anda dapat menambahkan atau menarik soal setelah membuat ujian.
                                    </DialogDescription>
                                </DialogHeader>
                                <div className="grid gap-4 py-4">
                                    <div className="grid gap-2">
                                        <Label htmlFor="title" className="font-bold">Judul Ujian</Label>
                                        <Input
                                            id="title"
                                            placeholder="Contoh: Ujian Akhir Semester IPA Class X"
                                            value={form.data.title}
                                            onChange={e => form.setData('title', e.target.value)}
                                            required
                                            className="h-11 rounded-xl font-medium"
                                        />
                                        {form.errors.title && <p className="text-xs text-rose-500">{form.errors.title}</p>}
                                    </div>

                                    <div className="grid gap-2">
                                        <Label htmlFor="subject" className="font-bold">Mata Pelajaran</Label>
                                        <Input
                                            id="subject"
                                            placeholder="Contoh: Fisika / Matematika / Bahasa Indonesia"
                                            value={form.data.subject}
                                            onChange={e => form.setData('subject', e.target.value)}
                                            required
                                            className="h-11 rounded-xl font-medium"
                                        />
                                        {form.errors.subject && <p className="text-xs text-rose-500">{form.errors.subject}</p>}
                                    </div>

                                    <div className="grid gap-2">
                                        <Label htmlFor="duration" className="font-bold">Durasi (Menit)</Label>
                                        <Input
                                            id="duration"
                                            type="number"
                                            min={1}
                                            max={300}
                                            value={form.data.duration_minutes}
                                            onChange={e => form.setData('duration_minutes', e.target.value)}
                                            placeholder="Contoh: 30, 45, 60"
                                            className="h-11 rounded-xl font-medium"
                                        />
                                        {form.errors.duration_minutes && <p className="text-xs text-rose-500">{form.errors.duration_minutes}</p>}
                                    </div>

                                    <div className="flex items-center gap-2.5 pt-2">
                                        <input
                                            type="checkbox"
                                            id="is_active"
                                            checked={form.data.is_active}
                                            onChange={e => form.setData('is_active', e.target.checked)}
                                            className="size-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                        />
                                        <Label htmlFor="is_active" className="cursor-pointer font-semibold text-slate-700 text-sm">
                                            Aktifkan Ujian ini sekarang (bisa dikerjakan siswa)
                                        </Label>
                                    </div>
                                </div>
                                <DialogFooter>
                                    <Button type="button" variant="outline" onClick={() => setIsCreateOpen(false)} className="rounded-xl font-bold">Batal</Button>
                                    <Button type="submit" disabled={form.processing} className="bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl">
                                        Simpan Ujian
                                    </Button>
                                </DialogFooter>
                            </form>
                        </DialogContent>
                    </Dialog>
                </div>

                {/* Stats Cards */}
                <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <Card className="border border-slate-200/80 shadow-md shadow-blue-950/5 bg-white rounded-3xl overflow-hidden">
                        <CardHeader className="flex flex-row items-center justify-between pb-2">
                            <CardTitle className="text-xs font-extrabold uppercase tracking-wider text-slate-500">Total Ujian</CardTitle>
                            <div className="p-2.5 bg-blue-50 text-blue-600 rounded-2xl border border-blue-100">
                                <BookOpen className="size-5" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="text-3xl font-black text-slate-900">{totalExams}</div>
                            <p className="text-xs text-slate-500 font-medium mt-1">Paket ujian terdaftar</p>
                        </CardContent>
                    </Card>

                    <Card className="border border-slate-200/80 shadow-md shadow-blue-950/5 bg-white rounded-3xl overflow-hidden">
                        <CardHeader className="flex flex-row items-center justify-between pb-2">
                            <CardTitle className="text-xs font-extrabold uppercase tracking-wider text-slate-500">Total Soal Terhubung</CardTitle>
                            <div className="p-2.5 bg-blue-50 text-blue-600 rounded-2xl border border-blue-100">
                                <FileQuestion className="size-5" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="text-3xl font-black text-slate-900">{totalQuestions}</div>
                            <p className="text-xs text-slate-500 font-medium mt-1">Soal siap diuji</p>
                        </CardContent>
                    </Card>

                    <Card className="border border-slate-200/80 shadow-md shadow-blue-950/5 bg-white rounded-3xl overflow-hidden">
                        <CardHeader className="flex flex-row items-center justify-between pb-2">
                            <CardTitle className="text-xs font-extrabold uppercase tracking-wider text-slate-500">Ujian Aktif</CardTitle>
                            <div className="p-2.5 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-100">
                                <CheckCircle2 className="size-5" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="text-3xl font-black text-slate-900">{activeExams}</div>
                            <p className="text-xs text-slate-500 font-medium mt-1">Status aktif untuk siswa</p>
                        </CardContent>
                    </Card>
                </div>

                {/* Exams List Table */}
                <Card className="border border-slate-200/80 shadow-md shadow-blue-950/5 bg-white rounded-3xl overflow-hidden">
                    <CardHeader className="border-b border-slate-100 pb-4">
                        <CardTitle className="text-xl font-black text-slate-900">Daftar Paket Ujian</CardTitle>
                        <CardDescription className="text-slate-500 text-xs sm:text-sm font-medium">
                            Kelola pertanyaan, sesuaikan durasi, dan lihat rekap pengerjaan siswa.
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="p-0 sm:p-6">
                        {exams.length === 0 ? (
                            <div className="text-center py-16 text-slate-500">
                                <BookOpen className="size-14 mx-auto stroke-1 opacity-40 mb-3 text-blue-600" />
                                <p className="font-extrabold text-lg text-slate-900">Belum ada ujian yang dibuat</p>
                                <p className="text-xs text-slate-500">Klik tombol "Buat Ujian Baru" untuk membuat paket ujian pertama Anda.</p>
                            </div>
                        ) : (
                            <div className="overflow-x-auto">
                                <table className="w-full text-sm text-left">
                                    <thead className="bg-slate-50 text-slate-600 text-xs uppercase font-black border-b border-slate-200">
                                        <tr>
                                            <th className="px-5 py-4">Judul & Mata Pelajaran</th>
                                            <th className="px-4 py-4">Durasi</th>
                                            <th className="px-4 py-4">Soal Terhubung</th>
                                            <th className="px-4 py-4">Peserta</th>
                                            <th className="px-4 py-4">Status</th>
                                            <th className="px-5 py-4 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-slate-100">
                                        {exams.map(exam => (
                                            <tr key={exam.id} className="hover:bg-blue-50/40 transition-colors">
                                                <td className="px-5 py-4 font-medium">
                                                    <div className="font-black text-slate-900 text-base">{exam.title}</div>
                                                    <div className="text-xs text-blue-600 font-extrabold mt-0.5">{exam.subject}</div>
                                                </td>
                                                <td className="px-4 py-4 whitespace-nowrap">
                                                    <div className="flex items-center gap-1.5 text-slate-700 font-bold text-xs">
                                                        <Clock className="size-3.5 text-blue-600" />
                                                        <span>{exam.duration_minutes} menit</span>
                                                    </div>
                                                </td>
                                                <td className="px-4 py-4 whitespace-nowrap">
                                                    <Badge variant="outline" className="gap-1 bg-blue-50 border-blue-200 text-blue-800 font-extrabold px-3 py-1 text-xs">
                                                        <FileQuestion className="size-3.5 text-blue-600" />
                                                        {exam.questions_count} Soal
                                                    </Badge>
                                                </td>
                                                <td className="px-4 py-4 whitespace-nowrap">
                                                    <div className="flex items-center gap-1.5 text-slate-700 font-bold text-xs">
                                                        <Users className="size-3.5 text-indigo-600" />
                                                        <span>{exam.sessions_count} Siswa</span>
                                                    </div>
                                                </td>
                                                <td className="px-4 py-4 whitespace-nowrap">
                                                    {exam.is_active ? (
                                                        <Badge className="bg-emerald-600 text-white font-extrabold text-xs px-2.5 py-0.5">
                                                            Aktif
                                                        </Badge>
                                                    ) : (
                                                        <Badge variant="secondary" className="text-slate-500 font-bold text-xs">
                                                            Nonaktif
                                                        </Badge>
                                                    )}
                                                </td>
                                                <td className="px-5 py-4 text-right whitespace-nowrap">
                                                    <div className="flex items-center justify-end gap-2">
                                                        <Link href={`/admin/exams/${exam.id}`}>
                                                            <Button size="sm" className="gap-1.5 text-xs bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-xs h-9">
                                                                <FileQuestion className="size-3.5" />
                                                                Kelola Soal
                                                            </Button>
                                                        </Link>

                                                        <Link href={`/admin/exams/${exam.id}/results`}>
                                                            <Button size="sm" variant="outline" className="gap-1.5 text-xs font-bold border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl h-9">
                                                                <Users className="size-3.5 text-blue-600" />
                                                                Hasil
                                                            </Button>
                                                        </Link>

                                                        <Button size="sm" variant="ghost" className="rounded-xl h-9 w-9 p-0" onClick={() => handleEditOpen(exam)}>
                                                            <Edit className="size-4 text-slate-600" />
                                                        </Button>

                                                        <Button size="sm" variant="ghost" className="text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-xl h-9 w-9 p-0" onClick={() => handleDelete(exam.id)}>
                                                            <Trash2 className="size-4" />
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

            {/* Edit Modal */}
            <Dialog open={!!editingExam} onOpenChange={open => !open && setEditingExam(null)}>
                <DialogContent className="sm:max-w-[480px]">
                    <form onSubmit={handleEditSubmit}>
                        <DialogHeader>
                            <DialogTitle className="text-xl font-bold">Edit Ujian</DialogTitle>
                            <DialogDescription>Ubah detail informasi paket ujian.</DialogDescription>
                        </DialogHeader>
                        <div className="grid gap-4 py-4">
                            <div className="grid gap-2">
                                <Label htmlFor="edit-title" className="font-bold">Judul Ujian</Label>
                                <Input
                                    id="edit-title"
                                    value={editForm.data.title}
                                    onChange={e => editForm.setData('title', e.target.value)}
                                    required
                                    className="h-11 rounded-xl font-medium"
                                />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="edit-subject" className="font-bold">Mata Pelajaran</Label>
                                <Input
                                    id="edit-subject"
                                    value={editForm.data.subject}
                                    onChange={e => editForm.setData('subject', e.target.value)}
                                    required
                                    className="h-11 rounded-xl font-medium"
                                />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="edit-duration" className="font-bold">Durasi (Menit)</Label>
                                <Input
                                    id="edit-duration"
                                    type="number"
                                    min={1}
                                    max={300}
                                    value={editForm.data.duration_minutes}
                                    onChange={e => editForm.setData('duration_minutes', e.target.value)}
                                    placeholder="Contoh: 30, 45, 60"
                                    className="h-11 rounded-xl font-medium"
                                />
                            </div>

                            <div className="flex items-center gap-2.5 pt-2">
                                <input
                                    type="checkbox"
                                    id="edit-is_active"
                                    checked={editForm.data.is_active}
                                    onChange={e => editForm.setData('is_active', e.target.checked)}
                                    className="size-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                />
                                <Label htmlFor="edit-is_active" className="cursor-pointer font-semibold text-slate-700 text-sm">
                                    Aktifkan Ujian ini (bisa dikerjakan siswa)
                                </Label>
                            </div>
                        </div>
                        <DialogFooter>
                            <Button type="button" variant="outline" onClick={() => setEditingExam(null)} className="rounded-xl font-bold">Batal</Button>
                            <Button type="submit" disabled={editForm.processing} className="bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl">
                                Simpan Perubahan
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </AppLayout>
    );
}
