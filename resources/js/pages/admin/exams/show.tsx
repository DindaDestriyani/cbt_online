import { useState, useEffect } from 'react';
import { Head, Link, useForm, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Badge } from '@/components/ui/badge';
import { Textarea } from '@/components/ui/textarea';
import { Plus, ArrowLeft, Clock, FileQuestion, Trash2, Edit, Check, Download, Shuffle, Search, Filter, BookOpen, CheckCircle2, Zap, CheckCheck } from 'lucide-react';

interface Question {
    id: number;
    subject?: string | null;
    question_text: string;
    option_a: string;
    option_b: string;
    option_c: string;
    option_d: string;
    option_e: string;
    correct_option: 'a' | 'b' | 'c' | 'd' | 'e';
}

interface Exam {
    id: number;
    title: string;
    subject: string;
    duration_minutes: number;
    is_active: boolean;
    questions: Question[];
}

interface Props {
    exam: Exam;
    all_bank_questions: Question[];
    bank_subjects: string[];
}

export default function AdminExamShow({ exam, all_bank_questions = [], bank_subjects = [] }: Props) {
    const [isAddOpen, setIsAddOpen] = useState(false);
    const [isPullBankOpen, setIsPullBankOpen] = useState(false);
    const [isPullRandomOpen, setIsPullRandomOpen] = useState(false);
    const [editingQuestion, setEditingQuestion] = useState<Question | null>(null);

    // Array of attached question IDs in this exam
    const attachedIds = exam.questions.map(q => q.id);

    // Selected question IDs state for the Bank Soal modal
    const [selectedQuestionIds, setSelectedQuestionIds] = useState<number[]>(attachedIds);
    const [bankSearch, setBankSearch] = useState('');
    const [bankSubject, setBankSubject] = useState(exam.subject || '');

    // Sync selectedQuestionIds when modal opens or exam.questions updates
    const handleOpenPullBankModal = () => {
        setSelectedQuestionIds(exam.questions.map(q => q.id));
        setIsPullBankOpen(true);
    };

    // Form for creating a new question in Bank Soal & attaching to Exam
    const form = useForm({
        subject: exam.subject,
        question_text: '',
        option_a: '',
        option_b: '',
        option_c: '',
        option_d: '',
        option_e: '',
        correct_option: 'a' as 'a' | 'b' | 'c' | 'd' | 'e',
    });

    // Form for editing existing question
    const editForm = useForm({
        subject: exam.subject,
        question_text: '',
        option_a: '',
        option_b: '',
        option_c: '',
        option_d: '',
        option_e: '',
        correct_option: 'a' as 'a' | 'b' | 'c' | 'd' | 'e',
    });

    // Form for pulling random questions
    const randomForm = useForm<{ subject: string; count: number | string }>({
        subject: exam.subject || '',
        count: 5,
    });

    const handleAddSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        form.post('/admin/questions', {
            onSuccess: () => {
                setIsAddOpen(false);
                form.reset({
                    subject: exam.subject,
                    question_text: '',
                    option_a: '',
                    option_b: '',
                    option_c: '',
                    option_d: '',
                    option_e: '',
                    correct_option: 'a',
                });
            },
        });
    };

    const handleEditOpen = (question: Question) => {
        setEditingQuestion(question);
        editForm.setData({
            subject: question.subject || exam.subject,
            question_text: question.question_text,
            option_a: question.option_a,
            option_b: question.option_b,
            option_c: question.option_c,
            option_d: question.option_d,
            option_e: question.option_e,
            correct_option: question.correct_option,
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

    const handleDetachQuestion = (questionId: number) => {
        if (confirm('Lepas soal ini dari ujian ini? Soal akan tetap tersimpan aman di Bank Soal.')) {
            router.delete(`/admin/exams/${exam.id}/questions/${questionId}/detach`);
        }
    };

    const handleToggleQuestionSelect = (id: number) => {
        if (selectedQuestionIds.includes(id)) {
            setSelectedQuestionIds(selectedQuestionIds.filter(qId => qId !== id));
        } else {
            setSelectedQuestionIds([...selectedQuestionIds, id]);
        }
    };

    const handleSelectAllBank = () => {
        if (selectedQuestionIds.length === filteredBankQuestions.length) {
            setSelectedQuestionIds([]);
        } else {
            setSelectedQuestionIds(filteredBankQuestions.map(q => q.id));
        }
    };

    const handlePullSelectedSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        router.post(`/admin/exams/${exam.id}/attach-questions`, {
            question_ids: selectedQuestionIds,
        }, {
            onSuccess: () => {
                setIsPullBankOpen(false);
            },
        });
    };

    const handlePullRandomSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        const parsedCount = Math.max(1, parseInt(String(randomForm.data.count)) || 1);
        router.post(`/admin/exams/${exam.id}/random-questions`, {
            subject: randomForm.data.subject,
            count: parsedCount,
        }, {
            onSuccess: () => {
                setIsPullRandomOpen(false);
            },
        });
    };

    const filteredBankQuestions = all_bank_questions.filter(q => {
        const matchesText = !bankSearch || q.question_text.toLowerCase().includes(bankSearch.toLowerCase());
        const matchesSubject = !bankSubject || q.subject === bankSubject;
        return matchesText && matchesSubject;
    });

    return (
        <AppLayout
            breadcrumbs={[
                { title: 'Kelola Ujian', href: '/admin/exams' },
                { title: exam.title, href: `/admin/exams/${exam.id}` },
            ]}
        >
            <Head title={`Kelola Soal - ${exam.title}`} />

            <div className="flex flex-col gap-6 p-6">
                {/* Back button & Title Header */}
                <div className="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                    <div className="flex items-center gap-3">
                        <Link href="/admin/exams">
                            <Button variant="outline" size="icon" className="size-9 rounded-xl border-slate-200">
                                <ArrowLeft className="size-4" />
                            </Button>
                        </Link>
                        <div>
                            <div className="flex items-center gap-2">
                                <h1 className="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{exam.title}</h1>
                                {exam.is_active ? (
                                    <Badge className="bg-emerald-100 text-emerald-700 font-semibold">Aktif</Badge>
                                ) : (
                                    <Badge variant="secondary">Nonaktif</Badge>
                                )}
                            </div>
                            <p className="text-slate-500 text-sm flex items-center gap-3 mt-1">
                                <span>Mapel: <strong className="text-blue-600">{exam.subject}</strong></span>
                                <span>•</span>
                                <span className="flex items-center gap-1"><Clock className="size-3.5 text-blue-600" /> {exam.duration_minutes} Menit</span>
                                <span>•</span>
                                <span className="font-semibold text-slate-800">{exam.questions.length} Soal Terhubung</span>
                            </p>
                        </div>
                    </div>

                    {/* Action Buttons for Pulling / Adding Questions */}
                    <div className="flex flex-wrap items-center gap-2">
                        {/* 1. Pull Manual from Bank Soal */}
                        <Button
                            onClick={handleOpenPullBankModal}
                            className="gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-md shadow-blue-600/20"
                        >
                            <Download className="size-4" />
                            Pilih dari Bank Soal ({all_bank_questions.length})
                        </Button>

                        {/* 2. Pull Random Questions */}
                        <Button
                            onClick={() => setIsPullRandomOpen(true)}
                            variant="outline"
                            className="gap-2 border-indigo-200 text-indigo-700 hover:bg-indigo-50 font-bold"
                        >
                            <Shuffle className="size-4" />
                            Tarik Soal Acak
                        </Button>

                        {/* 3. Create New Question */}
                        <Button
                            onClick={() => setIsAddOpen(true)}
                            variant="secondary"
                            className="gap-2 font-bold"
                        >
                            <Plus className="size-4" />
                            Buat Soal Baru
                        </Button>
                    </div>
                </div>

                {/* Questions List */}
                <div className="space-y-4">
                    {exam.questions.length === 0 ? (
                        <Card className="border border-dashed border-slate-300 text-center py-12 bg-white">
                            <CardContent className="flex flex-col items-center">
                                <BookOpen className="size-12 stroke-1 text-blue-600 opacity-60 mb-3" />
                                <p className="font-bold text-lg text-slate-900">Belum Ada Soal Terhubung di Ujian Ini</p>
                                <p className="text-sm text-slate-500 max-w-md mb-6">
                                    Admin dapat memilih dan menarik soal-soal yang ada di <strong>Bank Soal ({all_bank_questions.length} Soal Tersedia)</strong> atau memilih secara acak.
                                </p>
                                <div className="flex flex-wrap gap-3 justify-center">
                                    <Button onClick={handleOpenPullBankModal} className="bg-blue-600 hover:bg-blue-700 text-white font-bold gap-2">
                                        <Download className="size-4" />
                                        Pilih dari Bank Soal
                                    </Button>
                                    <Button onClick={() => setIsPullRandomOpen(true)} variant="outline" className="border-indigo-200 text-indigo-700 font-bold gap-2">
                                        <Shuffle className="size-4" />
                                        Tarik Soal Acak
                                    </Button>
                                </div>
                            </CardContent>
                        </Card>
                    ) : (
                        exam.questions.map((q, idx) => (
                            <Card key={q.id} className="border border-slate-200 shadow-xs hover:border-blue-300 transition-colors bg-white">
                                <CardHeader className="flex flex-row items-start justify-between pb-3">
                                    <div className="flex gap-3">
                                        <Badge className="size-8 rounded-xl bg-blue-100 text-blue-700 font-bold flex items-center justify-center p-0 shrink-0 text-sm">
                                            {idx + 1}
                                        </Badge>
                                        <div className="space-y-1">
                                            {q.subject && (
                                                <Badge variant="outline" className="bg-slate-50 border-slate-200 text-slate-600 text-xs mb-1">
                                                    Bank: {q.subject}
                                                </Badge>
                                            )}
                                            <p className="font-bold text-slate-900 text-base leading-relaxed whitespace-pre-line">
                                                {q.question_text}
                                            </p>
                                        </div>
                                    </div>
                                    <div className="flex items-center gap-1 shrink-0 ml-4">
                                        <Button size="sm" variant="ghost" onClick={() => handleEditOpen(q)} title="Edit Soal">
                                            <Edit className="size-4 text-slate-500" />
                                        </Button>
                                        <Button
                                            size="sm"
                                            variant="ghost"
                                            className="text-rose-600 hover:text-rose-700 hover:bg-rose-50"
                                            onClick={() => handleDetachQuestion(q.id)}
                                            title="Lepas Soal dari Ujian Ini"
                                        >
                                            <Trash2 className="size-4" />
                                        </Button>
                                    </div>
                                </CardHeader>
                                <CardContent className="pt-0">
                                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                                        {(['a', 'b', 'c', 'd', 'e'] as const).map(optKey => {
                                            const isCorrect = q.correct_option === optKey;
                                            const optionText = q[`option_${optKey}`];
                                            return (
                                                <div
                                                    key={optKey}
                                                    className={`p-3 rounded-xl border text-sm flex items-start gap-2.5 ${
                                                        isCorrect
                                                            ? 'bg-emerald-50 border-emerald-200 text-emerald-950 font-semibold'
                                                            : 'bg-slate-50/70 border-slate-200 text-slate-600'
                                                    }`}
                                                >
                                                    <span
                                                        className={`size-5 rounded-full text-xs font-bold flex items-center justify-center shrink-0 uppercase ${
                                                            isCorrect
                                                                ? 'bg-emerald-600 text-white'
                                                                : 'bg-slate-200 text-slate-600'
                                                        }`}
                                                    >
                                                        {optKey}
                                                    </span>
                                                    <span className="flex-1">{optionText}</span>
                                                    {isCorrect && <Check className="size-4 text-emerald-600 shrink-0" />}
                                                </div>
                                            );
                                        })}
                                    </div>
                                </CardContent>
                            </Card>
                        ))
                    )}
                </div>
            </div>

            {/* MODAL 1: Tarik / Pilih Soal dari Bank Soal (SELURUH 50 SOAL TETAP TAMPIL) */}
            <Dialog open={isPullBankOpen} onOpenChange={setIsPullBankOpen}>
                <DialogContent className="sm:max-w-[760px] max-h-[88vh] flex flex-col p-0 overflow-hidden">
                    <DialogHeader className="p-6 pb-3">
                        <DialogTitle className="text-xl font-bold flex items-center gap-2">
                            <Download className="size-5 text-blue-600" />
                            Kelola / Tarik Soal dari Bank Soal ({all_bank_questions.length} Soal)
                        </DialogTitle>
                        <DialogDescription>
                            Centang soal yang ingin dimasukkan ke ujian <strong>{exam.title}</strong>. Soal yang sudah terhubung akan bertanda hijau.
                        </DialogDescription>

                        {/* Search & Filter Bar */}
                        <div className="flex flex-wrap items-center gap-3 pt-3">
                            <div className="flex-1 min-w-[200px]">
                                <div className="relative">
                                    <Search className="absolute left-3 top-2.5 size-4 text-slate-400" />
                                    <Input
                                        placeholder="Cari kata kunci soal..."
                                        value={bankSearch}
                                        onChange={e => setBankSearch(e.target.value)}
                                        className="pl-9 h-9 text-xs"
                                    />
                                </div>
                            </div>
                            <div className="w-[200px]">
                                <select
                                    className="w-full h-9 px-3 rounded-md border border-slate-300 bg-white text-xs text-slate-700"
                                    value={bankSubject}
                                    onChange={e => setBankSubject(e.target.value)}
                                >
                                    <option value="">Semua Mapel</option>
                                    {bank_subjects.map(subj => (
                                        <option key={subj} value={subj}>{subj}</option>
                                    ))}
                                </select>
                            </div>
                        </div>
                    </DialogHeader>

                    {/* Bank Questions Scrollable List */}
                    <div className="flex-1 overflow-y-auto p-6 pt-0 divide-y divide-slate-100">
                        {filteredBankQuestions.length === 0 ? (
                            <div className="text-center py-10 text-slate-500">
                                <FileQuestion className="size-10 mx-auto stroke-1 opacity-50 mb-2 text-blue-600" />
                                <p className="font-semibold text-sm">Tidak ada soal yang cocok</p>
                                <p className="text-xs text-slate-400">Coba ubah filter mata pelajaran atau kata kunci pencarian.</p>
                            </div>
                        ) : (
                            <>
                                <div className="flex items-center justify-between py-2 text-xs font-semibold text-slate-500 sticky top-0 bg-white z-10 border-b border-slate-100">
                                    <span>Menampilkan {filteredBankQuestions.length} Soal Master</span>
                                    <div className="flex items-center gap-3">
                                        <span className="text-blue-600 font-bold">
                                            {selectedQuestionIds.length} Soal Dicenang
                                        </span>
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="sm"
                                            onClick={handleSelectAllBank}
                                            className="h-7 text-xs text-blue-600 font-bold hover:bg-blue-50"
                                        >
                                            {selectedQuestionIds.length === filteredBankQuestions.length ? 'Batal Pilih Semua' : 'Pilih Semua'}
                                        </Button>
                                    </div>
                                </div>

                                {filteredBankQuestions.map((q, index) => {
                                    const isChecked = selectedQuestionIds.includes(q.id);
                                    const isCurrentlyAttached = attachedIds.includes(q.id);
                                    return (
                                        <label
                                            key={q.id}
                                            className={`flex items-start gap-3 py-3 px-3 rounded-xl cursor-pointer transition-all my-1 ${
                                                isChecked
                                                    ? 'bg-blue-50/80 border-2 border-blue-500 shadow-xs'
                                                    : 'bg-white hover:bg-slate-50 border border-slate-200'
                                            }`}
                                        >
                                            <input
                                                type="checkbox"
                                                checked={isChecked}
                                                onChange={() => handleToggleQuestionSelect(q.id)}
                                                className="mt-1 size-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 shrink-0"
                                            />
                                            <div className="flex-1 text-sm">
                                                <div className="flex items-center gap-2 mb-1 flex-wrap">
                                                    <span className="text-xs font-bold text-slate-500">#{index + 1}</span>
                                                    {q.subject && (
                                                        <Badge variant="outline" className="text-[10px] bg-white text-slate-700">
                                                            {q.subject}
                                                        </Badge>
                                                    )}
                                                    {isCurrentlyAttached ? (
                                                        <Badge className="bg-emerald-100 text-emerald-800 border-emerald-300 text-[10px] font-bold gap-1">
                                                            <CheckCircle2 className="size-3 text-emerald-600" />
                                                            Terhubung di Ujian Ini
                                                        </Badge>
                                                    ) : (
                                                        <Badge variant="secondary" className="text-[10px]">
                                                            Belum Terhubung
                                                        </Badge>
                                                    )}
                                                    <span className="text-[10px] text-slate-600 font-bold bg-slate-100 px-1.5 py-0.5 rounded uppercase">
                                                        Kunci: {q.correct_option}
                                                    </span>
                                                </div>
                                                <p className="font-semibold text-slate-900 leading-snug">{q.question_text}</p>
                                            </div>
                                        </label>
                                    );
                                })}
                            </>
                        )}
                    </div>

                    <DialogFooter className="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                        <span className="text-xs text-slate-500 font-medium">
                            {selectedQuestionIds.length} dari {all_bank_questions.length} soal akan terhubung di ujian ini.
                        </span>
                        <div className="flex items-center gap-2">
                            <Button type="button" variant="outline" onClick={() => setIsPullBankOpen(false)}>
                                Batal
                            </Button>
                            <Button
                                onClick={handlePullSelectedSubmit}
                                className="bg-blue-600 hover:bg-blue-700 text-white font-bold gap-2 shadow-md shadow-blue-600/20"
                            >
                                <Download className="size-4" />
                                Simpan Pilihan ({selectedQuestionIds.length}) Soal
                            </Button>
                        </div>
                    </DialogFooter>
                </DialogContent>
            </Dialog>

            {/* MODAL 2: Tarik Soal Acak dari Bank Soal */}
            <Dialog open={isPullRandomOpen} onOpenChange={setIsPullRandomOpen}>
                <DialogContent className="sm:max-w-[480px]">
                    <form onSubmit={handlePullRandomSubmit}>
                        <DialogHeader>
                            <DialogTitle className="flex items-center gap-2 text-indigo-900">
                                <Shuffle className="size-5 text-indigo-600" />
                                Tarik Soal Acak
                            </DialogTitle>
                            <DialogDescription>
                                Sistem akan memilihkan beberapa soal dari Bank Soal secara acak untuk langsung dimasukkan ke ujian ini.
                            </DialogDescription>
                        </DialogHeader>

                        <div className="grid gap-4 py-4">
                            <div className="grid gap-2">
                                <Label htmlFor="random-subject">Kategori / Mata Pelajaran</Label>
                                <select
                                    id="random-subject"
                                    className="w-full h-10 px-3 rounded-md border border-slate-300 bg-white text-sm focus:ring-2 focus:ring-indigo-500"
                                    value={randomForm.data.subject}
                                    onChange={e => randomForm.setData('subject', e.target.value)}
                                >
                                    <option value="">Semua Mata Pelajaran</option>
                                    {bank_subjects.map(subj => (
                                        <option key={subj} value={subj}>{subj}</option>
                                    ))}
                                </select>
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="random-count">Jumlah Soal yang Ingin Ditarik</Label>
                                <Input
                                    id="random-count"
                                    type="number"
                                    min={1}
                                    max={100}
                                    value={randomForm.data.count}
                                    onChange={e => randomForm.setData('count', e.target.value)}
                                    required
                                />
                                <p className="text-xs text-slate-500">Soal akan ditarik secara acak dari Bank Soal.</p>
                            </div>
                        </div>

                        <DialogFooter>
                            <Button type="button" variant="outline" onClick={() => setIsPullRandomOpen(false)}>Batal</Button>
                            <Button type="submit" disabled={randomForm.processing} className="bg-indigo-600 hover:bg-indigo-700 text-white font-bold gap-2">
                                <Shuffle className="size-4" />
                                Tarik Soal Acak
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>

            {/* MODAL 3: Buat Soal Baru */}
            <Dialog open={isAddOpen} onOpenChange={setIsAddOpen}>
                <DialogContent className="sm:max-w-[640px] max-h-[90vh] overflow-y-auto">
                    <form onSubmit={handleAddSubmit}>
                        <DialogHeader>
                            <DialogTitle>Buat Soal Baru ke Bank Soal</DialogTitle>
                            <DialogDescription>
                                Soal baru ini akan otomatis disimpan di Bank Soal dan terhubung ke Ujian ini.
                            </DialogDescription>
                        </DialogHeader>
                        <div className="grid gap-4 py-4">
                            <div className="grid gap-2">
                                <Label htmlFor="q_subject">Mata Pelajaran / Topik</Label>
                                <Input
                                    id="q_subject"
                                    value={form.data.subject}
                                    onChange={e => form.setData('subject', e.target.value)}
                                />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="q_text">Teks Pertanyaan</Label>
                                <Textarea
                                    id="q_text"
                                    rows={3}
                                    placeholder="Tuliskan pertanyaan ujian di sini..."
                                    value={form.data.question_text}
                                    onChange={e => form.setData('question_text', e.target.value)}
                                    required
                                />
                            </div>

                            {(['a', 'b', 'c', 'd', 'e'] as const).map(opt => (
                                <div key={opt} className="grid gap-2">
                                    <div className="flex items-center justify-between">
                                        <Label htmlFor={`opt_${opt}`} className="uppercase font-bold text-slate-700 text-xs">
                                            Pilihan {opt.toUpperCase()}
                                        </Label>
                                        <label className="flex items-center gap-1.5 text-xs cursor-pointer">
                                            <input
                                                type="radio"
                                                name="correct_option"
                                                value={opt}
                                                checked={form.data.correct_option === opt}
                                                onChange={() => form.setData('correct_option', opt)}
                                                className="text-emerald-600 focus:ring-emerald-500"
                                            />
                                            <span className={form.data.correct_option === opt ? 'font-bold text-emerald-600' : 'text-slate-500'}>
                                                {form.data.correct_option === opt ? '✓ Kunci Jawaban' : 'Jadikan Kunci'}
                                            </span>
                                        </label>
                                    </div>
                                    <Input
                                        id={`opt_${opt}`}
                                        placeholder={`Jawaban opsi ${opt.toUpperCase()}`}
                                        value={form.data[`option_${opt}`]}
                                        onChange={e => form.setData(`option_${opt}` as any, e.target.value)}
                                        required
                                    />
                                </div>
                            ))}
                        </div>
                        <DialogFooter>
                            <Button type="button" variant="outline" onClick={() => setIsAddOpen(false)}>Batal</Button>
                            <Button type="submit" disabled={form.processing} className="bg-blue-600 hover:bg-blue-700 text-white font-bold">
                                Simpan Soal
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>

            {/* MODAL 4: Edit Question */}
            <Dialog open={!!editingQuestion} onOpenChange={open => !open && setEditingQuestion(null)}>
                <DialogContent className="sm:max-w-[640px] max-h-[90vh] overflow-y-auto">
                    <form onSubmit={handleEditSubmit}>
                        <DialogHeader>
                            <DialogTitle>Edit Soal</DialogTitle>
                            <DialogDescription>Perbarui pertanyaan dan opsi jawaban di Bank Soal.</DialogDescription>
                        </DialogHeader>
                        <div className="grid gap-4 py-4">
                            <div className="grid gap-2">
                                <Label htmlFor="edit_q_subject">Mata Pelajaran / Topik</Label>
                                <Input
                                    id="edit_q_subject"
                                    value={editForm.data.subject}
                                    onChange={e => editForm.setData('subject', e.target.value)}
                                />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="edit_q_text">Teks Pertanyaan</Label>
                                <Textarea
                                    id="edit_q_text"
                                    rows={3}
                                    value={editForm.data.question_text}
                                    onChange={e => editForm.setData('question_text', e.target.value)}
                                    required
                                />
                            </div>

                            {(['a', 'b', 'c', 'd', 'e'] as const).map(opt => (
                                <div key={opt} className="grid gap-2">
                                    <div className="flex items-center justify-between">
                                        <Label htmlFor={`edit_opt_${opt}`} className="uppercase font-bold text-slate-700 text-xs">
                                            Pilihan {opt.toUpperCase()}
                                        </Label>
                                        <label className="flex items-center gap-1.5 text-xs cursor-pointer">
                                            <input
                                                type="radio"
                                                name="edit_correct_option"
                                                value={opt}
                                                checked={editForm.data.correct_option === opt}
                                                onChange={() => editForm.setData('correct_option', opt)}
                                                className="text-emerald-600 focus:ring-emerald-500"
                                            />
                                            <span className={editForm.data.correct_option === opt ? 'font-bold text-emerald-600' : 'text-slate-500'}>
                                                {editForm.data.correct_option === opt ? '✓ Kunci Jawaban' : 'Jadikan Kunci'}
                                            </span>
                                        </label>
                                    </div>
                                    <Input
                                        id={`edit_opt_${opt}`}
                                        value={editForm.data[`option_${opt}`]}
                                        onChange={e => editForm.setData(`option_${opt}` as any, e.target.value)}
                                        required
                                    />
                                </div>
                            ))}
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
