'use client';

import { useState, useEffect, Suspense } from 'react';
import { useRouter, useSearchParams } from 'next/navigation';
import Link from 'next/link';
import { Building2, GraduationCap, UserPlus, Sparkles } from 'lucide-react';

function SignupForm() {
  const router = useRouter();
  const searchParams = useSearchParams();

  const [role, setRole] = useState('scientist');
  const [username, setUsername] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState('');

  useEffect(() => {
    const roleParam = searchParams.get('role');
    if (roleParam === 'company' || roleParam === 'scientist') {
      setRole(roleParam);
    }
  }, [searchParams]);

  const handleSignup = async (e) => {
    e.preventDefault();
    setLoading(true);
    setMessage('');

    try {
      const res = await fetch('/api/v1/auth/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, email, password, role })
      });
      const data = await res.json();

      if (data.success) {
        localStorage.setItem('user', JSON.stringify(data.user));
        localStorage.setItem('token', data.token);
        setMessage('Muvaffaqiyatli roʻyxatdan oʻtdingiz!');
        setTimeout(() => {
          window.location.href = '/dashboard';
        }, 1000);
      } else {
        setMessage(data.message || 'Xatolik yuz berdi');
      }
    } catch (e) {
      setMessage('Xatolik: ' + e.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="glass max-w-lg w-full rounded-3xl p-8 space-y-8 border border-slate-800 relative">
      <div className="text-center space-y-2">
        <div className="w-12 h-12 rounded-2xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center mx-auto mb-3 border border-cyan-500/20">
          <UserPlus className="w-6 h-6" />
        </div>
        <h1 className="text-2xl font-bold text-white font-display">Ro'yxatdan O'tish</h1>
        <p className="text-xs text-slate-400">Platformada ishtirok etish uchun rolingizni tanlang</p>
      </div>

      {/* Role Selector Tabs */}
      <div className="grid grid-cols-2 gap-3 p-1.5 rounded-2xl bg-slate-900 border border-slate-800">
        <button
          type="button"
          onClick={() => setRole('scientist')}
          className={`py-3 rounded-xl text-xs font-semibold flex items-center justify-center gap-2 transition-all ${
            role === 'scientist'
              ? 'bg-cyan-500 text-slate-950 shadow-md'
              : 'text-slate-400 hover:text-white'
          }`}
        >
          <GraduationCap className="w-4 h-4" />
          <span>Olim / Izlanuvchi</span>
        </button>

        <button
          type="button"
          onClick={() => setRole('company')}
          className={`py-3 rounded-xl text-xs font-semibold flex items-center justify-center gap-2 transition-all ${
            role === 'company'
              ? 'bg-cyan-500 text-slate-950 shadow-md'
              : 'text-slate-400 hover:text-white'
          }`}
        >
          <Building2 className="w-4 h-4" />
          <span>Qurilish Korxonasi</span>
        </button>
      </div>

      {message && (
        <div className={`p-3.5 rounded-2xl text-xs font-medium text-center ${message.includes('Muvaffaqiyatli') ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30'}`}>
          {message}
        </div>
      )}

      <form onSubmit={handleSignup} className="space-y-4">
        <div className="form-group">
          <label>{role === 'company' ? 'Korxona Nomi' : 'Foydalanuvchi Nomi (Username)'}</label>
          <input
            type="text"
            required
            placeholder={role === 'company' ? 'Masalan: Apex Construction LLC' : 'Masalan: prof_usmanov'}
            value={username}
            onChange={(e) => setUsername(e.target.value)}
            className="form-input"
          />
        </div>

        <div className="form-group">
          <label>Elektron Pochta (Email)</label>
          <input
            type="email"
            required
            placeholder="email@domain.uz"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            className="form-input"
          />
        </div>

        <div className="form-group">
          <label>Parol</label>
          <input
            type="password"
            required
            placeholder="Kamida 6 ta belgi"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            className="form-input"
          />
        </div>

        <button type="submit" disabled={loading} className="w-full btn-primary py-3.5 text-xs font-bold rounded-2xl">
          {loading ? 'Yaratilmoqda...' : 'Roʻyxatdan oʻtish'}
        </button>
      </form>

      <div className="text-center text-xs text-slate-400 pt-4 border-t border-slate-800">
        Akkountingiz bormi?{' '}
        <Link href="/login" className="text-cyan-400 hover:underline font-semibold">
          Tizimga kirish
        </Link>
      </div>
    </div>
  );
}

export default function SignupPage() {
  return (
    <div className="container py-16 flex items-center justify-center min-h-[85vh]">
      <Suspense fallback={<div className="text-xs text-slate-400">Yuklanmoqda...</div>}>
        <SignupForm />
      </Suspense>
    </div>
  );
}
