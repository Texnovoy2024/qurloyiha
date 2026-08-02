'use client';

import { useState, useEffect } from 'react';
import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { Building2, Search, PlusCircle, User, LogIn, Award, ShieldCheck, Sparkles } from 'lucide-react';

export default function Navbar() {
  const pathname = usePathname();
  const [currentUser, setCurrentUser] = useState(null);

  useEffect(() => {
    // Check localStorage for logged in user session
    const savedUser = localStorage.getItem('user');
    if (savedUser) {
      try {
        setCurrentUser(JSON.parse(savedUser));
      } catch (e) {}
    }
  }, []);

  const handleLogout = () => {
    localStorage.removeItem('user');
    setCurrentUser(null);
    window.location.href = '/';
  };

  return (
    <nav className="glass-nav sticky top-0 z-50 py-3.5 px-4">
      <div className="container flex items-center justify-between gap-4">
        {/* Brand Logo */}
        <Link href="/" className="flex items-center gap-3 group">
          <div className="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyan-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-cyan-500/20 group-hover:scale-105 transition-transform">
            <Building2 className="w-5 h-5" />
          </div>
          <div>
            <span className="font-bold text-xl tracking-tight text-white flex items-center gap-1.5 font-display">
              Qurilish<span className="text-cyan-400">Loyiha</span>
              <Sparkles className="w-4 h-4 text-amber-400 animate-pulse" />
            </span>
            <span className="text-[10px] text-slate-400 block -mt-1 tracking-wider uppercase">Innovation Ecosystem</span>
          </div>
        </Link>

        {/* Navigation Links */}
        <div className="hidden md:flex items-center gap-1 bg-slate-900/60 p-1.5 rounded-full border border-slate-800">
          <Link
            href="/"
            className={`px-4 py-2 rounded-full text-sm font-medium transition-colors ${
              pathname === '/' ? 'bg-cyan-500/20 text-cyan-400 border border-cyan-500/30' : 'text-slate-300 hover:text-white'
            }`}
          >
            Bosh sahifa
          </Link>
          <Link
            href="/problems"
            className={`px-4 py-2 rounded-full text-sm font-medium transition-colors ${
              pathname.startsWith('/problems') ? 'bg-cyan-500/20 text-cyan-400 border border-cyan-500/30' : 'text-slate-300 hover:text-white'
            }`}
          >
            Muammolar & Muhandislik Masalalari
          </Link>
          <Link
            href="/dashboard"
            className={`px-4 py-2 rounded-full text-sm font-medium transition-colors ${
              pathname === '/dashboard' ? 'bg-cyan-500/20 text-cyan-400 border border-cyan-500/30' : 'text-slate-300 hover:text-white'
            }`}
          >
            Dashboard
          </Link>
        </div>

        {/* User Actions */}
        <div className="flex items-center gap-3">
          <Link
            href="/problems/create"
            className="hidden sm:inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold rounded-lg bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-md hover:shadow-emerald-500/25 transition-all hover:-translate-y-0.5"
          >
            <PlusCircle className="w-4 h-4" />
            Masala joylash
          </Link>

          {currentUser ? (
            <div className="flex items-center gap-3">
              <Link
                href="/profile"
                className="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-800/80 border border-slate-700 text-xs font-medium text-slate-200 hover:border-cyan-500/50 transition-all"
              >
                <div className="w-6 h-6 rounded-full bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold text-xs">
                  {currentUser.username ? currentUser.username[0].toUpperCase() : 'U'}
                </div>
                <span className="hidden md:inline">{currentUser.username}</span>
                <span className="text-[10px] px-1.5 py-0.5 rounded bg-cyan-950 text-cyan-400 border border-cyan-800 uppercase">
                  {currentUser.role || 'User'}
                </span>
              </Link>
              <button
                onClick={handleLogout}
                className="text-xs text-rose-400 hover:text-rose-300 px-2 py-1 transition-colors"
              >
                Chiqish
              </button>
            </div>
          ) : (
            <div className="flex items-center gap-2">
              <Link
                href="/login"
                className="px-4 py-2 text-xs font-semibold text-slate-200 hover:text-white transition-colors flex items-center gap-1.5"
              >
                <LogIn className="w-4 h-4 text-cyan-400" />
                Kirish
              </Link>
              <Link
                href="/signup"
                className="px-4 py-2 text-xs font-semibold rounded-lg bg-cyan-500 hover:bg-cyan-400 text-slate-950 transition-colors shadow-sm shadow-cyan-500/20"
              >
                Ro'yxatdan o'tish
              </Link>
            </div>
          )}
        </div>
      </div>
    </nav>
  );
}
