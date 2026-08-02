'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import { LogIn, Building2, ShieldCheck, Sparkles, User } from 'lucide-react';

export default function LoginPage() {
  const router = useRouter();
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState('');

  const handleLogin = async (e) => {
    e.preventDefault();
    setLoading(true);
    setMessage('');

    try {
      const res = await fetch('/api/v1/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password })
      });
      const data = await res.json();

      if (data.success) {
        localStorage.setItem('user', JSON.stringify(data.user));
        localStorage.setItem('token', data.token);
        setMessage('Xush kelibsiz!');
        setTimeout(() => {
          window.location.href = '/dashboard';
        }, 1000);
      } else {
        setMessage(data.message || 'Login yoki parol xato');
      }
    } catch (e) {
      setMessage('Xatolik: ' + e.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="container py-16 flex items-center justify-center min-h-[80vh]">
      <div className="glass max-w-md w-full rounded-3xl p-8 space-y-8 border border-slate-800 relative">
        <div className="text-center space-y-2">
          <div className="w-12 h-12 rounded-2xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center mx-auto mb-3 border border-cyan-500/20">
            <LogIn className="w-6 h-6" />
          </div>
          <h1 className="text-2xl font-bold text-white font-display">Tizimga Kirish</h1>
          <p className="text-xs text-slate-400">QurilishLoyiha platformasiga xush kelibsiz</p>
        </div>

        {message && (
          <div className={`p-3.5 rounded-2xl text-xs font-medium text-center ${message.includes('Xush') ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30'}`}>
            {message}
          </div>
        )}

        <form onSubmit={handleLogin} className="space-y-5">
          <div className="form-group">
            <label>Foydalanuvchi nomi yoki Email</label>
            <input
              type="text"
              required
              placeholder="username yoki email"
              value={username}
              onChange={(e) => setUsername(e.target.value)}
              className="form-input"
            />
          </div>

          <div className="form-group">
            <label>Parol</label>
            <input
              type="password"
              required
              placeholder="••••••••"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="form-input"
            />
          </div>

          <button type="submit" disabled={loading} className="w-full btn-primary py-3.5 text-xs font-bold rounded-2xl">
            {loading ? 'Tekshirilmoqda...' : 'Kirish'}
          </button>
        </form>

        <div className="text-center text-xs text-slate-400 pt-4 border-t border-slate-800">
          Akkountingiz yo'qmi?{' '}
          <Link href="/signup" className="text-cyan-400 hover:underline font-semibold">
            Ro'yxatdan o'tish
          </Link>
        </div>
      </div>
    </div>
  );
}
