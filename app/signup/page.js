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
    <div className="clean-card max-w-lg w-full p-8 space-y-8">
      <div className="text-center space-y-2">
        <div className="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center mx-auto mb-3 border border-sky-100">
          <UserPlus className="w-6 h-6" />
        </div>
        <h1 className="text-2xl font-bold text-slate-900 font-display">Ro'yxatdan O'tish</h1>
        <p className="text-xs text-slate-500">Platformada ishtirok etish uchun rolingizni tanlang</p>
      </div>

      {/* Role Selector Tabs */}
      <div className="grid grid-cols-2 gap-2 p-1.5 rounded-xl bg-slate-100 border border-slate-200">
        <button
          type="button"
          onClick={() => setRole('scientist')}
          className={`py-2.5 rounded-lg text-xs font-semibold flex items-center justify-center gap-2 transition-all ${
            role === 'scientist'
              ? 'bg-white text-sky-700 shadow-sm'
              : 'text-slate-600 hover:text-slate-900'
          }`}
        >
          <GraduationCap className="w-4 h-4" />
          <span>Olim / Izlanuvchi</span>
        </button>

        <button
          type="button"
          onClick={() => setRole('company')}
          className={`py-2.5 rounded-lg text-xs font-semibold flex items-center justify-center gap-2 transition-all ${
            role === 'company'
              ? 'bg-white text-sky-700 shadow-sm'
              : 'text-slate-600 hover:text-slate-900'
          }`}
        >
          <Building2 className="w-4 h-4" />
          <span>Qurilish Korxonasi</span>
        </button>
      </div>

      {message && (
        <div className={`p-3.5 rounded-xl text-xs font-semibold text-center ${message.includes('Muvaffaqiyatli') ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200'}`}>
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

        <button type="submit" disabled={loading} className="w-full btn-primary py-3.5 text-xs font-bold rounded-xl shadow-md">
          {loading ? 'Yaratilmoqda...' : 'Roʻyxatdan oʻtish'}
        </button>
      </form>

      <div className="text-center text-xs text-slate-500 pt-4 border-t border-slate-100">
        Akkountingiz bormi?{' '}
        <Link href="/login" className="text-sky-600 hover:underline font-semibold">
          Tizimga kirish
        </Link>
      </div>
    </div>
  );
}

export default function SignupPage() {
  return (
    <div className="max-w-lg mx-auto px-4 py-16 flex items-center justify-center min-h-[80vh]">
      <Suspense fallback={<div className="text-xs text-slate-500">Yuklanmoqda...</div>}>
        <SignupForm />
      </Suspense>
    </div>
  );
}
