import { useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    GraduationCap, Lock, Mail, User, ArrowRight,
    Eye, EyeOff, ShieldCheck, CheckCircle,
    BookOpenCheck, UserPlus, ListChecks,
} from 'lucide-react';

export default function Register() {
    const { data, setData, post, processing, errors } = useForm({
        name: '', email: '', password: '', password_confirmation: '',
    });
    const [showPassword, setShowPassword] = useState(false);
    const handleSubmit = (e: React.FormEvent) => { e.preventDefault(); post('/register'); };

    return (
        <>
            <Head title="Daftar Siswa - CBT Ujian Online" />

            <div style={{
                position: 'fixed', inset: 0, display: 'flex',
                fontFamily: '"Inter", -apple-system, BlinkMacSystemFont, sans-serif',
                zIndex: 9999,
            }}>
                {/* ── LEFT PANEL ── */}
                <div style={{
                    width: '42%', height: '100%', flexShrink: 0,
                    background: 'linear-gradient(160deg, #0a1628 0%, #1e3a8a 55%, #1d4ed8 100%)',
                    display: 'flex', flexDirection: 'column',
                    position: 'relative', overflow: 'hidden',
                }}>
                    {/* Decorative circles */}
                    <div style={{ position: 'absolute', bottom: -160, right: -160, width: 480, height: 480, borderRadius: '50%', border: '1px solid rgba(255,255,255,0.06)', pointerEvents: 'none' }} />
                    <div style={{ position: 'absolute', bottom: -80, right: -80, width: 300, height: 300, borderRadius: '50%', border: '1px solid rgba(255,255,255,0.04)', pointerEvents: 'none' }} />
                    <div style={{ position: 'absolute', top: -100, right: -100, width: 350, height: 350, borderRadius: '50%', background: 'radial-gradient(circle, rgba(96,165,250,0.12) 0%, transparent 70%)', pointerEvents: 'none' }} />

                    {/* ── LOGO ── */}
                    <div style={{ position: 'relative', zIndex: 2, padding: '44px 50px 0' }}>
                        <div style={{ display: 'flex', alignItems: 'center', gap: 13 }}>
                            <div style={{ width: 48, height: 48, borderRadius: 14, background: 'rgba(255,255,255,0.1)', border: '1px solid rgba(255,255,255,0.14)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                                <GraduationCap style={{ width: 24, height: 24, color: '#bfdbfe' }} />
                            </div>
                            <div>
                                <p style={{ color: '#bfdbfe', fontWeight: 800, fontSize: 17, letterSpacing: '0.05em', margin: 0, textTransform: 'uppercase' }}>CBT Online</p>
                                <p style={{ color: 'rgba(191,219,254,0.5)', fontSize: 11.5, margin: '3px 0 0' }}>Sistem Ujian Berbasis Komputer</p>
                            </div>
                        </div>
                    </div>

                    {/* ── HERO ── */}
                    <div style={{ position: 'relative', zIndex: 2, flex: 1, display: 'flex', flexDirection: 'column', justifyContent: 'center', padding: '0 50px' }}>

                        <div style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '5px 12px', borderRadius: 6, background: 'rgba(255,255,255,0.08)', border: '1px solid rgba(255,255,255,0.1)', width: 'fit-content', marginBottom: 22 }}>
                            <div style={{ width: 6, height: 6, borderRadius: '50%', background: '#86efac' }} />
                            <span style={{ color: 'rgba(191,219,254,0.85)', fontSize: 11.5, fontWeight: 500 }}>Pendaftaran Gratis</span>
                        </div>

                        <h1 style={{ fontSize: 30, fontWeight: 800, color: '#eff6ff', lineHeight: 1.25, letterSpacing: '-0.02em', margin: '0 0 14px 0' }}>
                            Buat Akun &<br />
                            <span style={{ color: '#93c5fd' }}>Mulai Ujian Sekarang</span>
                        </h1>

                        <p style={{ color: 'rgba(191,219,254,0.5)', fontSize: 13.5, lineHeight: 1.7, margin: '0 0 36px 0', maxWidth: 300 }}>
                            Daftar dalam hitungan menit dan akses seluruh fitur platform CBT sekolah.
                        </p>

                        <div style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
                            {[
                                { icon: <CheckCircle style={{ width: 14, height: 14 }} />, text: 'Akses seluruh ujian & latihan soal' },
                                { icon: <ListChecks style={{ width: 14, height: 14 }} />, text: 'Pantau progres & riwayat nilai' },
                                { icon: <BookOpenCheck style={{ width: 14, height: 14 }} />, text: 'Lihat pembahasan setelah ujian' },
                            ].map((f, i) => (
                                <div key={i} style={{ display: 'flex', alignItems: 'center', gap: 11 }}>
                                    <div style={{ width: 30, height: 30, borderRadius: 8, background: 'rgba(255,255,255,0.08)', border: '1px solid rgba(255,255,255,0.08)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#93c5fd', flexShrink: 0 }}>
                                        {f.icon}
                                    </div>
                                    <p style={{ color: 'rgba(191,219,254,0.7)', fontSize: 13, margin: 0, fontWeight: 500 }}>{f.text}</p>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* ── FOOTER ── */}
                    <div style={{ position: 'relative', zIndex: 2, padding: '22px 50px 34px', borderTop: '1px solid rgba(255,255,255,0.06)', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                        <div style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
                            <ShieldCheck style={{ width: 13, height: 13, color: '#86efac' }} />
                            <span style={{ color: 'rgba(191,219,254,0.4)', fontSize: 11.5 }}>Data aman & terlindungi</span>
                        </div>
                        <span style={{ color: 'rgba(191,219,254,0.25)', fontSize: 11 }}>© 2026 CBT Online</span>
                    </div>
                </div>

                {/* ── RIGHT PANEL ── */}
                <div style={{ flex: 1, background: '#ffffff', display: 'flex', alignItems: 'center', justifyContent: 'center', padding: '32px 56px', overflow: 'hidden' }}>
                    <div style={{ width: '100%', maxWidth: 400 }}>

                        {/* Header */}
                        <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 6 }}>
                            <div style={{ width: 40, height: 40, borderRadius: 11, background: '#eff6ff', border: '1.5px solid #bfdbfe', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#1d4ed8', flexShrink: 0 }}>
                                <UserPlus style={{ width: 18, height: 18 }} />
                            </div>
                            <h2 style={{ fontSize: 22, fontWeight: 800, color: '#111827', letterSpacing: '-0.02em', margin: 0 }}>Buat Akun Siswa</h2>
                        </div>
                        <p style={{ color: '#6b7280', fontSize: 13.5, margin: '0 0 26px 0' }}>
                            Lengkapi data berikut untuk mendaftar ke portal ujian sekolah.
                        </p>

                        {/* Form */}
                        <form onSubmit={handleSubmit} style={{ display: 'flex', flexDirection: 'column', gap: 15 }}>

                            {/* Nama */}
                            <div>
                                <Label htmlFor="name" style={{ display: 'block', fontSize: 13, fontWeight: 600, color: '#374151', marginBottom: 6 }}>
                                    Nama Lengkap
                                </Label>
                                <div style={{ position: 'relative' }}>
                                    <User style={{ position: 'absolute', left: 13, top: '50%', transform: 'translateY(-50%)', width: 15, height: 15, color: '#9ca3af' }} />
                                    <Input id="name" type="text" required autoFocus placeholder="Nama lengkap Anda" value={data.name} onChange={e => setData('name', e.target.value)}
                                        style={{ paddingLeft: 38, height: 42, borderRadius: 8, border: '1.5px solid #e5e7eb', background: '#f9fafb', fontSize: 14, width: '100%', boxSizing: 'border-box', color: '#111827' }}
                                        className="focus-visible:ring-0 focus-visible:border-blue-500" />
                                </div>
                                {errors.name && <p style={{ color: '#ef4444', fontSize: 11.5, marginTop: 4 }}>{errors.name}</p>}
                            </div>

                            {/* Email */}
                            <div>
                                <Label htmlFor="email" style={{ display: 'block', fontSize: 13, fontWeight: 600, color: '#374151', marginBottom: 6 }}>
                                    Alamat Email
                                </Label>
                                <div style={{ position: 'relative' }}>
                                    <Mail style={{ position: 'absolute', left: 13, top: '50%', transform: 'translateY(-50%)', width: 15, height: 15, color: '#9ca3af' }} />
                                    <Input id="email" type="email" required placeholder="siswa@sekolah.com" value={data.email} onChange={e => setData('email', e.target.value)}
                                        style={{ paddingLeft: 38, height: 42, borderRadius: 8, border: '1.5px solid #e5e7eb', background: '#f9fafb', fontSize: 14, width: '100%', boxSizing: 'border-box', color: '#111827' }}
                                        className="focus-visible:ring-0 focus-visible:border-blue-500" />
                                </div>
                                {errors.email && <p style={{ color: '#ef4444', fontSize: 11.5, marginTop: 4 }}>{errors.email}</p>}
                            </div>

                            {/* Password — 2 columns */}
                            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>
                                <div>
                                    <Label htmlFor="password" style={{ display: 'block', fontSize: 13, fontWeight: 600, color: '#374151', marginBottom: 6 }}>Kata Sandi</Label>
                                    <div style={{ position: 'relative' }}>
                                        <Lock style={{ position: 'absolute', left: 13, top: '50%', transform: 'translateY(-50%)', width: 14, height: 14, color: '#9ca3af' }} />
                                        <Input id="password" type={showPassword ? 'text' : 'password'} required placeholder="Min. 8 karakter" value={data.password} onChange={e => setData('password', e.target.value)}
                                            style={{ paddingLeft: 36, paddingRight: 36, height: 42, borderRadius: 8, border: '1.5px solid #e5e7eb', background: '#f9fafb', fontSize: 13.5, width: '100%', boxSizing: 'border-box', color: '#111827' }}
                                            className="focus-visible:ring-0 focus-visible:border-blue-500" />
                                        <button type="button" onClick={() => setShowPassword(!showPassword)}
                                            style={{ position: 'absolute', right: 10, top: '50%', transform: 'translateY(-50%)', background: 'none', border: 'none', cursor: 'pointer', color: '#9ca3af', display: 'flex', padding: 0 }}>
                                            {showPassword ? <EyeOff style={{ width: 14, height: 14 }} /> : <Eye style={{ width: 14, height: 14 }} />}
                                        </button>
                                    </div>
                                    {errors.password && <p style={{ color: '#ef4444', fontSize: 11, marginTop: 3 }}>{errors.password}</p>}
                                </div>
                                <div>
                                    <Label htmlFor="password_confirmation" style={{ display: 'block', fontSize: 13, fontWeight: 600, color: '#374151', marginBottom: 6 }}>Ulangi Sandi</Label>
                                    <div style={{ position: 'relative' }}>
                                        <Lock style={{ position: 'absolute', left: 13, top: '50%', transform: 'translateY(-50%)', width: 14, height: 14, color: '#9ca3af' }} />
                                        <Input id="password_confirmation" type={showPassword ? 'text' : 'password'} required placeholder="Ulangi sandi" value={data.password_confirmation} onChange={e => setData('password_confirmation', e.target.value)}
                                            style={{ paddingLeft: 36, height: 42, borderRadius: 8, border: '1.5px solid #e5e7eb', background: '#f9fafb', fontSize: 13.5, width: '100%', boxSizing: 'border-box', color: '#111827' }}
                                            className="focus-visible:ring-0 focus-visible:border-blue-500" />
                                    </div>
                                    {errors.password_confirmation && <p style={{ color: '#ef4444', fontSize: 11, marginTop: 3 }}>{errors.password_confirmation}</p>}
                                </div>
                            </div>

                            {/* Terms */}
                            <p style={{ fontSize: 12, color: '#9ca3af', margin: '2px 0 0', lineHeight: 1.6 }}>
                                Dengan mendaftar Anda menyetujui{' '}
                                <span style={{ color: '#1d4ed8', fontWeight: 500, cursor: 'pointer' }}>Syarat Penggunaan</span>
                                {' '}dan{' '}
                                <span style={{ color: '#1d4ed8', fontWeight: 500, cursor: 'pointer' }}>Kebijakan Privasi</span>.
                            </p>

                            {/* Submit */}
                            <button type="submit" disabled={processing} style={{
                                height: 44, borderRadius: 8, border: 'none', cursor: processing ? 'not-allowed' : 'pointer',
                                background: processing ? '#e5e7eb' : 'linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%)',
                                color: processing ? '#9ca3af' : '#ffffff',
                                fontWeight: 700, fontSize: 14, display: 'flex', alignItems: 'center', justifyContent: 'center',
                                gap: 8, width: '100%', letterSpacing: '0.01em',
                                boxShadow: processing ? 'none' : '0 2px 12px rgba(29,78,216,0.35)',
                                transition: 'all 0.2s',
                            }}>
                                {processing
                                    ? <><div style={{ width: 16, height: 16, borderRadius: '50%', border: '2px solid #d1d5db', borderTopColor: '#9ca3af', animation: 'spin 0.7s linear infinite' }} /><span>Memproses...</span></>
                                    : <><span>Daftar Akun Sekarang</span><ArrowRight style={{ width: 16, height: 16 }} /></>}
                            </button>
                        </form>

                        {/* Footer */}
                        <p style={{ textAlign: 'center', fontSize: 13, color: '#6b7280', marginTop: 22, paddingTop: 18, borderTop: '1px solid #f3f4f6' }}>
                            Sudah punya akun?{' '}
                            <Link href="/login" style={{ fontWeight: 600, color: '#1d4ed8', textDecoration: 'none' }}
                                onMouseEnter={e => (e.currentTarget.style.textDecoration = 'underline')}
                                onMouseLeave={e => (e.currentTarget.style.textDecoration = 'none')}>
                                Masuk di Sini
                            </Link>
                        </p>
                    </div>
                </div>
            </div>

            <style>{`
                @keyframes spin { to { transform: rotate(360deg); } }
                html, body, #app { margin: 0 !important; padding: 0 !important; width: 100% !important; height: 100% !important; overflow: hidden !important; }
            `}</style>
        </>
    );
}
