import { useState } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Badge } from '@/components/ui/badge';
import { Textarea } from '@/components/ui/textarea';
import { Plus, BookOpen, Search, Filter, Trash2, Edit, CheckCircle, FileQuestion, Layers } from 'lucide-react';

interface QuestionItem {
    id: number;
    subject: string | null;
    question_text: string;
    option_a: string;
    option_b: string;
    option_c: string;
    option_d: string;
    option_e: string;
    correct_option: string;
    exams_count: number;
    created_at: string;
}

interface PaginatedQuestions {
    data: QuestionItem[];
    current_page: number;
    last_page: number;
    total: number;
}

interface Props {
    questions: PaginatedQuestions;
    subjects: string[];
    filters: {
        search: string;
        subject: string;
    };
}

export default function BankSoalIndex({ questions, subjects, filters }: Props) {
    const [isCreateOpen, setIsCreateOpen] = useState(false);
    const [editingQuestion, setEditingQuestion] = useState<QuestionItem | null>(null);
    const [searchQuery, setSearchQuery] = useState(filters.search || '');
    const [selectedSubject, setSelectedSubject] = useState(filters.subject || '');

    const form = useForm({
        subject: '',
        question_text: '',
        option_a: '',
        option_b: '',
        option_c: '',
        option_d: '',
        option_e: '',
        correct_option: 'a',
    });

    const editForm = useForm({
        subject: '',
        question_text: '',
        option_a: '',
        option_b: '',
        option_c: '',
        option_d: '',
        option_e: '',
        correct_option: 'a',
    });

    const handleSearchFilter = (e?: React.FormEvent) => {
        if (e) e.preventDefault();
        router.get('/admin/questions', {
            search: searchQuery,
            subject: selectedSubject,
        }, { preserveState: true });
    };

    const handleResetFilter = () => {
        setSearchQuery('');
        setSelectedSubject('');
        router.get('/admin/questions');
    };

    const handleCreateSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        form.post('/admin/questions', {
            onSuccess: () => {
                setIsCreateOpen(false);
                form.reset();
            },
        });
    };

    const handleEditOpen = (q: QuestionItem) => {
        setEditingQuestion(q);
        editForm.setData({
            subject: q.subject || '',
            question_text: q.question_text,
            option_a: q.option_a,
            option_b: q.option_b,
            option_c: q.option_c,
            option_d: q.option_d,
            option_e: q.option_e,
            correct_option: q.correct_option,
        });
    };

    const handleEditSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (!editingQuestion) return;
        editForm.put(`/admin/questions/${editingQuestion.id}`, {
            onSuccess: () => {
                setEditingQuestion(null);
            },
        });
    };

    const handleDelete = (id: number) => {
        if (confirm('Apakah Anda yakin ingin menghapus soal ini dari Bank Soal?')) {
            router.delete(`/admin/questions/${id}`);
        }
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Bank Soal', href: '/admin/questions' }]}>
            <Head title="Bank Soal Master - Admin" />

            <div className="flex flex-col gap-6 p-6">
                {/* Header Title & Create Button */}
                <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h1 className="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Bank Soal Master</h1>
                        <p className="text-slate-500 text-sm">Kelola semua repositori soal yang dapat ditarik ke paket ujian mana saja.</p>
                    </div>

                    <Dialog open={isCreateOpen} onOpenChange={setIsCreateOpen}>
                        <DialogTrigger asChild>
                            <Button className="gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-md shadow-blue-600/20">
                                <Plus className="size-4" />
                                Tambah Soal Baru
                            </Button>
                        </DialogTrigger>
                        <DialogContent className="sm:max-w-[640px] max-h-[90vh] overflow-y-auto">
                            <form onSubmit={handleCreateSubmit}>
                                <DialogHeader>
                                    <DialogTitle>Tambah Soal ke Bank Soal</DialogTitle>
                                    <DialogDescription>
                                        Buat pertanyaan beserta pilihan jawaban A sampai E dan kunci jawaban.
                                    </DialogDescription>
                                </DialogHeader>

                                <div className="grid gap-4 py-4">
                                    <div className="grid gap-2">
                                        <Label htmlFor="subject">Mata Pelajaran / Topik</Label>
                                        <Input
                                            id="subject"
                                            placeholder="Contoh: Matematika / Bahasa Indonesia / Informatika"
                                            value={form.data.subject}
                                            onChange={e => form.setData('subject', e.target.value)}
                                        />
                                    </div>

                                    <div className="grid gap-2">
                                        <Label htmlFor="question_text">Teks Pertanyaan</Label>
                                        <Textarea
                                            id="question_text"
                                            placeholder="Tuliskan pertanyaan soal di sini..."
                                            rows={3}
                                            value={form.data.question_text}
                                            onChange={e => form.setData('question_text', e.target.value)}
                                            required
                                        />
                                        {form.errors.question_text && <p className="text-xs text-rose-500">{form.errors.question_text}</p>}
                                    </div>

                                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <Label htmlFor="opt_a" className="text-xs font-semibold text-slate-700">Pilihan A</Label>
                                            <Input
                                                id="opt_a"
                                                value={form.data.option_a}
                                                onChange={e => form.setData('option_a', e.target.value)}
                                                required
                                            />
                                        </div>
                                        <div>
                                            <Label htmlFor="opt_b" className="text-xs font-semibold text-slate-700">Pilihan B</Label>
                                            <Input
                                                id="opt_b"
                                                value={form.data.option_b}
                                                onChange={e => form.setData('option_b', e.target.value)}
                                                required
                                            />
                                        </div>
                                        <div>
                                            <Label htmlFor="opt_c" className="text-xs font-semibold text-slate-700">Pilihan C</Label>
                                            <Input
                                                id="opt_c"
                                                value={form.data.option_c}
                                                onChange={e => form.setData('option_c', e.target.value)}
                                                required
                                            />
                                        </div>
                                        <div>
                                            <Label htmlFor="opt_d" className="text-xs font-semibold text-slate-700">Pilihan D</Label>
                                            <Input
                                                id="opt_d"
                                                value={form.data.option_d}
                                                onChange={e => form.setData('option_d', e.target.value)}
                                                required
                                            />
                                        </div>
                                        <div className="sm:col-span-2">
                                            <Label htmlFor="opt_e" className="text-xs font-semibold text-slate-700">Pilihan E</Label>
                                            <Input
                                                id="opt_e"
                                                value={form.data.option_e}
                                                onChange={e => form.setData('option_e', e.target.value)}
                                                required
                                            />
                                        </div>
                                    </div>

                                    <div className="grid gap-2 pt-2">
                                        <Label htmlFor="correct_option" className="font-semibold text-slate-900">Kunci Jawaban Benar</Label>
                                        <select
                                            id="correct_option"
                                            className="w-full h-10 px-3 rounded-md border border-slate-300 bg-white text-sm focus:ring-2 focus:ring-blue-500"
                                            value={form.data.correct_option}
                                            onChange={e => form.setData('correct_option', e.target.value)}
                                            required
                                        >
                                            <option value="a">A</option>
                                            <option value="b">B</option>
                                            <option value="c">C</option>
                                            <option value="d">D</option>
                                            <option value="e">E</option>
                                        </select>
                                    </div>
                                </div>

                                <DialogFooter>
                                    <Button type="button" variant="outline" onClick={() => setIsCreateOpen(false)}>Batal</Button>
                                    <Button type="submit" disabled={form.processing} className="bg-blue-600 hover:bg-blue-700 text-white font-bold">
                                        Simpan ke Bank Soal
                                    </Button>
                                </DialogFooter>
                            </form>
                        </DialogContent>
                    </Dialog>
                </div>

                {/* Filter and Search Bar */}
                <Card className="border border-slate-200 shadow-xs bg-white">
                    <CardContent className="p-4">
                        <form onSubmit={handleSearchFilter} className="flex flex-wrap items-center gap-3">
                            <div className="flex-1 min-w-[240px]">
                                <div className="relative">
                                    <Search className="absolute left-3 top-2.5 size-4 text-slate-400" />
                                    <Input
                                        placeholder="Cari kata kunci soal..."
                                        value={searchQuery}
                                        onChange={e => setSearchQuery(e.target.value)}
                                        className="pl-9"
                                    />
                                </div>
                            </div>

                            <div className="w-full sm:w-[200px]">
                                <select
                                    className="w-full h-9 px-3 rounded-md border border-slate-300 bg-white text-sm text-slate-700"
                                    value={selectedSubject}
                                    onChange={e => setSelectedSubject(e.target.value)}
                                >
                                    <option value="">Semua Mapel</option>
                                    {subjects.map(subj => (
                                        <option key={subj} value={subj}>{subj}</option>
                                    ))}
                                </select>
                            </div>

                            <Button type="submit" size="sm" className="bg-slate-800 text-white font-semibold">
                                <Filter className="size-3.5 mr-1" /> Filter
                            </Button>

                            {(searchQuery || selectedSubject) && (
                                <Button type="button" variant="ghost" size="sm" onClick={handleResetFilter} className="text-slate-500">
                                    Reset
                                </Button>
                            )}
                        </form>
                    </CardContent>
                </Card>

                {/* Question List */}
                <Card className="border border-slate-200 shadow-xs bg-white">
                    <CardHeader className="flex flex-row items-center justify-between pb-3">
                        <div>
                            <CardTitle className="text-lg font-bold text-slate-900">Daftar Soal Master ({questions.total})</CardTitle>
                            <CardDescription className="text-slate-500">Soal-soal ini siap ditarik ke dalam paket ujian.</CardDescription>
                        </div>
                    </CardHeader>
                    <CardContent>
                        {questions.data.length === 0 ? (
                            <div className="text-center py-12 text-slate-500">
                                <FileQuestion className="size-12 mx-auto stroke-1 opacity-50 mb-3 text-blue-600" />
                                <p className="font-semibold text-base text-slate-800">Tidak ada soal yang ditemukan</p>
                                <p className="text-sm text-slate-500">Coba ubah kata kunci pencarian atau tambah soal baru.</p>
                            </div>
                        ) : (
                            <div className="flex flex-col divide-y divide-slate-100">
                                {questions.data.map((q, idx) => (
                                    <div key={q.id} className="py-4 first:pt-0 last:pb-0 flex flex-col gap-3">
                                        <div className="flex items-start justify-between gap-4">
                                            <div className="flex items-start gap-3">
                                                <span className="flex items-center justify-center size-7 rounded-full bg-blue-50 text-blue-700 text-xs font-bold shrink-0">
                                                    {(questions.current_page - 1) * 15 + idx + 1}
                                                </span>
                                                <div className="flex flex-col gap-1">
                                                    <div className="flex items-center gap-2 flex-wrap">
                                                        {q.subject && (
                                                            <Badge variant="outline" className="bg-slate-100 text-slate-700 border-slate-200 font-medium text-xs">
                                                                {q.subject}
                                                            </Badge>
                                                        )}
                                                        <Badge className="bg-blue-100 text-blue-800 border-blue-200 font-medium text-xs">
                                                            Terhubung di {q.exams_count} Ujian
                                                        </Badge>
                                                        <Badge className="bg-emerald-100 text-emerald-800 border-emerald-200 font-bold text-xs uppercase">
                                                            Kunci: {q.correct_option}
                                                        </Badge>
                                                    </div>
                                                    <p className="text-slate-900 font-medium text-base mt-1 whitespace-pre-wrap">{q.question_text}</p>
                                                </div>
                                            </div>

                                            <div className="flex items-center gap-1 shrink-0">
                                                <Button size="sm" variant="ghost" onClick={() => handleEditOpen(q)}>
                                                    <Edit className="size-4 text-slate-500" />
                                                </Button>
                                                <Button size="sm" variant="ghost" className="text-rose-600 hover:bg-rose-50" onClick={() => handleDelete(q.id)}>
                                                    <Trash2 className="size-4" />
                                                </Button>
                                            </div>
                                        </div>

                                        {/* Options Grid */}
                                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2 pl-10 text-xs">
                                            {(['a', 'b', 'c', 'd', 'e'] as const).map(opt => {
                                                const optText = q[`option_${opt}` as keyof QuestionItem];
                                                const isCorrect = q.correct_option.toLowerCase() === opt;
                                                return (
                                                    <div
                                                        key={opt}
                                                        className={`p-2 rounded-md border ${
                                                            isCorrect
                                                                ? 'bg-emerald-50 border-emerald-300 text-emerald-900 font-semibold'
                                                                : 'bg-slate-50 border-slate-200 text-slate-700'
                                                        }`}
                                                    >
                                                        <span className="font-bold uppercase mr-1">{opt}.</span> {String(optText)}
                                                    </div>
                                                );
                                            })}
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}

                        {/* Simple Pagination */}
                        {questions.last_page > 1 && (
                            <div className="flex items-center justify-between pt-6 border-t border-slate-100 text-sm">
                                <span className="text-slate-500">
                                    Halaman {questions.current_page} dari {questions.last_page}
                                </span>
                                <div className="flex items-center gap-2">
                                    <Button
                                        size="sm"
                                        variant="outline"
                                        disabled={questions.current_page === 1}
                                        onClick={() => router.get('/admin/questions', { page: questions.current_page - 1, search: searchQuery, subject: selectedSubject })}
                                    >
                                        Sebelumnya
                                    </Button>
                                    <Button
                                        size="sm"
                                        variant="outline"
                                        disabled={questions.current_page === questions.last_page}
                                        onClick={() => router.get('/admin/questions', { page: questions.current_page + 1, search: searchQuery, subject: selectedSubject })}
                                    >
                                        Selanjutnya
                                    </Button>
                                </div>
                            </div>
                        )}
                    </CardContent>
                </Card>
            </div>

            {/* Edit Modal */}
            <Dialog open={!!editingQuestion} onOpenChange={open => !open && setEditingQuestion(null)}>
                <DialogContent className="sm:max-w-[640px] max-h-[90vh] overflow-y-auto">
                    <form onSubmit={handleEditSubmit}>
                        <DialogHeader>
                            <DialogTitle>Edit Soal Bank Soal</DialogTitle>
                            <DialogDescription>Perbarui pertanyaan dan pilihan jawaban.</DialogDescription>
                        </DialogHeader>

                        <div className="grid gap-4 py-4">
                            <div className="grid gap-2">
                                <Label htmlFor="edit-subject">Mata Pelajaran / Topik</Label>
                                <Input
                                    id="edit-subject"
                                    value={editForm.data.subject}
                                    onChange={e => editForm.setData('subject', e.target.value)}
                                />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="edit-question_text">Teks Pertanyaan</Label>
                                <Textarea
                                    id="edit-question_text"
                                    rows={3}
                                    value={editForm.data.question_text}
                                    onChange={e => editForm.setData('question_text', e.target.value)}
                                    required
                                />
                            </div>

                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <Label htmlFor="edit-opt_a" className="text-xs font-semibold text-slate-700">Pilihan A</Label>
                                    <Input
                                        id="edit-opt_a"
                                        value={editForm.data.option_a}
                                        onChange={e => editForm.setData('option_a', e.target.value)}
                                        required
                                    />
                                </div>
                                <div>
                                    <Label htmlFor="edit-opt_b" className="text-xs font-semibold text-slate-700">Pilihan B</Label>
                                    <Input
                                        id="edit-opt_b"
                                        value={editForm.data.option_b}
                                        onChange={e => editForm.setData('option_b', e.target.value)}
                                        required
                                    />
                                </div>
                                <div>
                                    <Label htmlFor="edit-opt_c" className="text-xs font-semibold text-slate-700">Pilihan C</Label>
                                    <Input
                                        id="edit-opt_c"
                                        value={editForm.data.option_c}
                                        onChange={e => editForm.setData('option_c', e.target.value)}
                                        required
                                    />
                                </div>
                                <div>
                                    <Label htmlFor="edit-opt_d" className="text-xs font-semibold text-slate-700">Pilihan D</Label>
                                    <Input
                                        id="edit-opt_d"
                                        value={editForm.data.option_d}
                                        onChange={e => editForm.setData('option_d', e.target.value)}
                                        required
                                    />
                                </div>
                                <div className="sm:col-span-2">
                                    <Label htmlFor="edit-opt_e" className="text-xs font-semibold text-slate-700">Pilihan E</Label>
                                    <Input
                                        id="edit-opt_e"
                                        value={editForm.data.option_e}
                                        onChange={e => editForm.setData('option_e', e.target.value)}
                                        required
                                    />
                                </div>
                            </div>

                            <div className="grid gap-2 pt-2">
                                <Label htmlFor="edit-correct_option" className="font-semibold text-slate-900">Kunci Jawaban Benar</Label>
                                <select
                                    id="edit-correct_option"
                                    className="w-full h-10 px-3 rounded-md border border-slate-300 bg-white text-sm focus:ring-2 focus:ring-blue-500"
                                    value={editForm.data.correct_option}
                                    onChange={e => editForm.setData('correct_option', e.target.value)}
                                    required
                                >
                                    <option value="a">A</option>
                                    <option value="b">B</option>
                                    <option value="c">C</option>
                                    <option value="d">D</option>
                                    <option value="e">E</option>
                                </select>
                            </div>
                        </div>

                        <DialogFooter>
                            <Button type="button" variant="outline" onClick={() => setEditingQuestion(null)}>Batal</Button>
                            <Button type="submit" disabled={editForm.processing} className="bg-blue-600 hover:bg-blue-700 text-white font-bold">
                                Simpan Perubahan
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </AppLayout>
    );
}
