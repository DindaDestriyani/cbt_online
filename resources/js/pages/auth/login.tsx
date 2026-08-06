import { useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import {
    GraduationCap, Lock, Mail, ArrowRight,
    CheckCircle2, Eye, EyeOff, ShieldCheck,
    ClipboardCheck, Timer, BookOpenCheck,
} from 'lucide-react';

type Props = { status?: string };

export default function Login({ status }: Props) {
    const { data, setData, post, processing, errors } = useForm({
        email: '', password: '', remember: false,
    });
    const [showPassword, setShowPassword] = useState(false);

    const handleSubmit = (e: React.FormEvent) => { e.preventDefault(); post('/login'); };
    const fillCredentials = (email: string) => setData({ email, password: 'password', remember: true });

    return (
        <>
            <Head title="Masuk - CBT Ujian Online" />

            <div style={{
                position: 'fixed', inset: 0, display: 'flex',
                fontFamily: '"Inter", -apple-system, BlinkMacSystemFont, sans-serif',
                zIndex: 9999,
            }}>

                {/* ── LEFT PANEL ── */}
                <div style={{
                    width: '45%', height: '100%', flexShrink: 0,
                    background: 'linear-gradient(160deg, #0a1628 0%, #1e3a8a 55%, #1d4ed8 100%)',
                    display: 'flex', flexDirection: 'column',
                    position: 'relative', overflow: 'hidden',
                }}>
                    {/* Decorative circles */}
                    <div style={{ position: 'absolute', bottom: -160, right: -160, width: 480, height: 480, borderRadius: '50%', border: '1px solid rgba(255,255,255,0.06)', pointerEvents: 'none' }} />
                    <div style={{ position: 'absolute', bottom: -80, right: -80, width: 300, height: 300, borderRadius: '50%', border: '1px solid rgba(255,255,255,0.04)', pointerEvents: 'none' }} />
                    <div style={{ position: 'absolute', top: -100, right: -100, width: 350, height: 350, borderRadius: '50%', background: 'radial-gradient(circle, rgba(96,165,250,0.12) 0%, transparent 70%)', pointerEvents: 'none' }} />

                    {/* ── LOGO ── */}
                    <div style={{ position: 'relative', zIndex: 2, padding: '44px 52px 0' }}>
                        <div style={{ display: 'flex', alignItems: 'center', gap: 14 }}>
                            <div style={{
                                width: 48, height: 48, borderRadius: 14,
                                background: 'rgba(255,255,255,0.1)',
                                border: '1px solid rgba(255,255,255,0.15)',
                                display: 'flex', alignItems: 'center', justifyContent: 'center',
                            }}>
                                <GraduationCap style={{ width: 24, height: 24, color: '#bfdbfe' }} />
                            </div>
                            <div>
                                <p style={{ color: '#bfdbfe', fontWeight: 800, fontSize: 17, letterSpacing: '0.06em', margin: 0, textTransform: 'uppercase' }}>
                                    CBT Online
                                </p>
                                <p style={{ color: 'rgba(191,219,254,0.5)', fontSize: 11.5, margin: '3px 0 0', letterSpacing: '0.01em' }}>
                                    Sistem Ujian Berbasis Komputer
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* ── HERO CONTENT ── */}
                    <div style={{ position: 'relative', zIndex: 2, flex: 1, display: 'flex', flexDirection: 'column', justifyContent: 'center', padding: '0 52px' }}>

                        <div style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '5px 12px', borderRadius: 6, background: 'rgba(255,255,255,0.08)', border: '1px solid rgba(255,255,255,0.1)', width: 'fit-content', marginBottom: 24 }}>
                            <div style={{ width: 6, height: 6, borderRadius: '50%', background: '#86efac' }} />
                            <span style={{ color: 'rgba(191,219,254,0.85)', fontSize: 11.5, fontWeight: 500 }}>Portal Resmi Ujian Sekolah</span>
                        </div>

                        <h1 style={{ fontSize: 34, fontWeight: 800, color: '#eff6ff', lineHeight: 1.2, letterSpacing: '-0.025em', margin: '0 0 16px 0' }}>
                            Selamat Datang
                            <br />
                            <span style={{ color: '#93c5fd' }}>di Portal CBT</span>
                        </h1>

                        <p style={{ color: 'rgba(191,219,254,0.55)', fontSize: 14, lineHeight: 1.75, margin: '0 0 40px 0', maxWidth: 340 }}>
                            Platform ujian berbasis komputer yang modern, akurat, dan transparan untuk siswa.
                        </p>

                        {/* Features */}
                        <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
                            {[
                                { icon: <Timer style={{ width: 15, height: 15 }} />, title: 'Timer Ujian Real-Time', desc: 'Waktu pengerjaan terpantau otomatis.' },
                                { icon: <ClipboardCheck style={{ width: 15, height: 15 }} />, title: 'Navigasi Soal Cerdas', desc: 'Tandai soal ragu dan kembali kapan saja.' },
                                { icon: <BookOpenCheck style={{ width: 15, height: 15 }} />, title: 'Nilai & Pembahasan Instan', desc: 'Hasil tampil segera setelah ujian selesai.' },
                            ].map((f, i) => (
                                <div key={i} style={{ display: 'flex', alignItems: 'flex-start', gap: 12 }}>
                                    <div style={{ width: 32, height: 32, borderRadius: 8, background: 'rgba(255,255,255,0.08)', border: '1px solid rgba(255,255,255,0.08)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#93c5fd', flexShrink: 0, marginTop: 1 }}>
                                        {f.icon}
                                    </div>
                                    <div>
                                        <p style={{ color: '#eff6ff', fontSize: 13, fontWeight: 600, margin: '0 0 2px 0' }}>{f.title}</p>
                                        <p style={{ color: 'rgba(191,219,254,0.45)', fontSize: 12, margin: 0 }}>{f.desc}</p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* ── FOOTER ── */}
                    <div style={{ position: 'relative', zIndex: 2, padding: '24px 52px 36px', borderTop: '1px solid rgba(255,255,255,0.06)', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                        <div style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
                            <ShieldCheck style={{ width: 13, height: 13, color: '#86efac' }} />
                            <span style={{ color: 'rgba(191,219,254,0.4)', fontSize: 11.5 }}>Data aman & terlindungi</span>
                        </div>
                        <span style={{ color: 'rgba(191,219,254,0.25)', fontSize: 11 }}>© 2026 CBT Online</span>
                    </div>
                </div>

                {/* ── RIGHT PANEL ── */}
                <div style={{
                    flex: 1, background: '#ffffff',
                    display: 'flex', alignItems: 'center', justifyContent: 'center',
                    padding: '40px 56px', overflow: 'hidden',
                }}>
                    <div style={{ width: '100%', maxWidth: 360 }}>

                        {/* Form header */}
                        <div style={{ marginBottom: 28 }}>
                            <h2 style={{ fontSize: 24, fontWeight: 800, color: '#111827', letterSpacing: '-0.02em', margin: '0 0 6px 0' }}>
                                Masuk ke Akun
                            </h2>
                            <p style={{ color: '#6b7280', fontSize: 13.5, margin: 0 }}>
                                Masukkan email dan kata sandi Anda untuk melanjutkan.
                            </p>
                        </div>

                        {status && (
                            <div style={{ display: 'flex', alignItems: 'center', gap: 8, padding: '11px 14px', borderRadius: 8, background: '#f0fdf4', border: '1px solid #bbf7d0', color: '#166534', fontSize: 13, marginBottom: 20 }}>
                                <CheckCircle2 style={{ width: 14, height: 14, flexShrink: 0, color: '#22c55e' }} />
                                {status}
                            </div>
                        )}

                        <form onSubmit={handleSubmit} style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>

                            {/* Email */}
                            <div>
                                <Label htmlFor="email" style={{ display: 'block', fontSize: 13, fontWeight: 600, color: '#374151', marginBottom: 6 }}>
                                    Email
                                </Label>
                                <div style={{ position: 'relative' }}>
                                    <Mail style={{ position: 'absolute', left: 13, top: '50%', transform: 'translateY(-50%)', width: 15, height: 15, color: '#9ca3af' }} />
                                    <Input
                                        id="email" type="email" required autoFocus
                                        placeholder="nama@email.com"
                                        value={data.email} onChange={e => setData('email', e.target.value)}
                                        style={{ paddingLeft: 38, height: 42, borderRadius: 8, border: '1.5px solid #e5e7eb', background: '#f9fafb', fontSize: 14, width: '100%', boxSizing: 'border-box', color: '#111827', transition: 'border-color 0.15s' }}
                                        className="focus-visible:ring-0 focus-visible:border-blue-500"
                                    />
                                </div>
                                {errors.email && <p style={{ color: '#ef4444', fontSize: 11.5, marginTop: 4 }}>{errors.email}</p>}
                            </div>

                            {/* Password */}
                            <div>
                                <Label htmlFor="password" style={{ display: 'block', fontSize: 13, fontWeight: 600, color: '#374151', marginBottom: 6 }}>
                                    Kata Sandi
                                </Label>
                                <div style={{ position: 'relative' }}>
                                    <Lock style={{ position: 'absolute', left: 13, top: '50%', transform: 'translateY(-50%)', width: 15, height: 15, color: '#9ca3af' }} />
                                    <Input
                                        id="password" type={showPassword ? 'text' : 'password'} required
                                        placeholder="Kata sandi Anda"
                                        value={data.password} onChange={e => setData('password', e.target.value)}
                                        style={{ paddingLeft: 38, paddingRight: 40, height: 42, borderRadius: 8, border: '1.5px solid #e5e7eb', background: '#f9fafb', fontSize: 14, width: '100%', boxSizing: 'border-box', color: '#111827' }}
                                        className="focus-visible:ring-0 focus-visible:border-blue-500"
                                    />
                                    <button type="button" onClick={() => setShowPassword(!showPassword)}
                                        style={{ position: 'absolute', right: 12, top: '50%', transform: 'translateY(-50%)', background: 'none', border: 'none', cursor: 'pointer', color: '#9ca3af', padding: 0, display: 'flex', alignItems: 'center' }}>
                                        {showPassword ? <EyeOff style={{ width: 15, height: 15 }} /> : <Eye style={{ width: 15, height: 15 }} />}
                                    </button>
                                </div>
                                {errors.password && <p style={{ color: '#ef4444', fontSize: 11.5, marginTop: 4 }}>{errors.password}</p>}
                            </div>

                            {/* Remember me */}
                            <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                                <Checkbox id="remember" checked={data.remember}
                                    onCheckedChange={c => setData('remember', Boolean(c))}
                                    className="border-gray-300 data-[state=checked]:bg-blue-600 data-[state=checked]:border-blue-600" />
                                <Label htmlFor="remember" style={{ fontSize: 13, color: '#6b7280', cursor: 'pointer', fontWeight: 400 }}>
                                    Ingat sesi saya
                                </Label>
                            </div>

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
                                    : <><span>Masuk ke Portal</span><ArrowRight style={{ width: 16, height: 16 }} /></>}
                            </button>
                        </form>

                        {/* Divider */}
                        <div style={{ display: 'flex', alignItems: 'center', gap: 12, margin: '20px 0 16px' }}>
                            <div style={{ flex: 1, height: 1, background: '#f3f4f6' }} />
                            <span style={{ fontSize: 11.5, color: '#9ca3af', fontWeight: 500 }}>Akun Demo</span>
                            <div style={{ flex: 1, height: 1, background: '#f3f4f6' }} />
                        </div>

                        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 10, marginBottom: 24 }}>
                            {[
                                { label: 'Admin / Guru', email: 'admin@cbt.com', icon: <ShieldCheck style={{ width: 14, height: 14 }} />, color: '#1d4ed8', bg: '#eff6ff', border: '#bfdbfe' },
                                { label: 'Siswa', email: 'siswa@cbt.com', icon: <GraduationCap style={{ width: 14, height: 14 }} />, color: '#0369a1', bg: '#f0f9ff', border: '#bae6fd' },
                            ].map((item, i) => (
                                <button key={i} type="button" onClick={() => fillCredentials(item.email)}
                                    style={{ display: 'flex', alignItems: 'center', gap: 10, padding: '10px 12px', borderRadius: 8, border: '1.5px solid #e5e7eb', background: '#fafafa', cursor: 'pointer', textAlign: 'left', transition: 'all 0.15s', width: '100%' }}
                                    onMouseEnter={e => { const el = e.currentTarget; el.style.background = item.bg; el.style.borderColor = item.border; }}
                                    onMouseLeave={e => { const el = e.currentTarget; el.style.background = '#fafafa'; el.style.borderColor = '#e5e7eb'; }}>
                                    <div style={{ width: 30, height: 30, borderRadius: 7, background: item.bg, border: `1px solid ${item.border}`, display: 'flex', alignItems: 'center', justifyContent: 'center', color: item.color, flexShrink: 0 }}>
                                        {item.icon}
                                    </div>
                                    <div>
                                        <p style={{ fontSize: 12.5, fontWeight: 600, color: '#111827', margin: 0 }}>{item.label}</p>
                                        <p style={{ fontSize: 11, color: '#9ca3af', margin: 0 }}>{item.email}</p>
                                    </div>
                                </button>
                            ))}
                        </div>

                        <p style={{ textAlign: 'center', fontSize: 13, color: '#6b7280' }}>
                            Belum punya akun?{' '}
                            <Link href="/register" style={{ fontWeight: 600, color: '#1d4ed8', textDecoration: 'none' }}
                                onMouseEnter={e => (e.currentTarget.style.textDecoration = 'underline')}
                                onMouseLeave={e => (e.currentTarget.style.textDecoration = 'none')}>
                                Daftar Sekarang
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
