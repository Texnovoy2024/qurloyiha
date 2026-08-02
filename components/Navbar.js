'use client';

import { useState, useEffect } from 'react';
import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { Building2, PlusCircle, LogIn, UserPlus, User, LayoutDashboard, Sparkles, Menu, X } from 'lucide-react';

export default function Navbar() {
  const pathname = usePathname();
  const [user, setUser] = useState(null);
  const [mobileMenu, setMobileMenu] = useState(false);

  useEffect(() => {
    const savedUser = localStorage.getItem('user');
    if (savedUser) {
      try {
        setUser(JSON.parse(savedUser));
      } catch (e) {}
    }
  }, []);

  const handleLogout = () => {
    localStorage.removeItem('user');
    localStorage.removeItem('token');
    setUser(null);
    window.location.href = '/';
  };

  const navLinks = [
    { href: '/', label: 'Bosh sahifa' },
    { href: '/problems', label: 'Muammolar Katalogi' },
    { href: '/dashboard', label: 'Dashboard' }
  ];

  return (
    <header className="clean-nav sticky top-0 z-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        {/* Brand Logo */}
        <Link href="/" className="flex items-center gap-3">
          <div className="w-11 h-11 rounded-xl bg-gradient-to-tr from-sky-600 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-sky-600/20">
            <Building2 className="w-6 h-6" />
          </div>
          <div>
            <div className="font-extrabold text-xl text-slate-900 font-display tracking-tight flex items-center gap-1.5">
              <span>Qurilish</span>
              <span className="text-sky-600">Loyiha</span>
            </div>
            <p className="text-[10px] text-slate-500 font-medium tracking-wide uppercase">Innovatsiyalar Portali</p>
          </div>
        </Link>

        {/* Desktop Navigation Links */}
        <nav className="hidden md:flex items-center gap-1 bg-slate-100/80 p-1.5 rounded-full border border-slate-200">
          {navLinks.map((link) => {
            const active = pathname === link.href;
            return (
              <Link
                key={link.href}
                href={link.href}
                className={`px-4 py-2 text-xs font-semibold rounded-full transition-all ${
                  active
                    ? 'bg-white text-sky-700 shadow-sm'
                    : 'text-slate-600 hover:text-slate-900 hover:bg-white/50'
                }`}
              >
                {link.label}
              </Link>
            );
          })}
        </nav>

        {/* Action Buttons */}
        <div className="hidden md:flex items-center gap-3">
          <Link
            href="/problems/create"
            className="px-4 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-xs font-semibold flex items-center gap-2 shadow-sm transition-all"
          >
            <PlusCircle className="w-4 h-4" />
            <span>Masala joylash</span>
          </Link>

          {user ? (
            <div className="flex items-center gap-2">
              <Link
                href="/profile"
                className="px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-2 transition-all border border-slate-200"
              >
                <User className="w-4 h-4 text-sky-600" />
                <span>{user.username}</span>
              </Link>
              <button
                onClick={handleLogout}
                className="px-3 py-2.5 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors"
              >
                Chiqish
              </button>
            </div>
          ) : (
            <div className="flex items-center gap-2">
              <Link
                href="/login"
                className="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100 transition-all"
              >
                Kirish
              </Link>
              <Link
                href="/signup"
                className="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition-all shadow-sm"
              >
                Ro'yxatdan o'tish
              </Link>
            </div>
          )}
        </div>

        {/* Mobile menu toggle */}
        <button
          onClick={() => setMobileMenu(!mobileMenu)}
          className="md:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100"
        >
          {mobileMenu ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
        </button>
      </div>

      {/* Mobile Drawer */}
      {mobileMenu && (
        <div className="md:hidden bg-white border-b border-slate-200 px-4 py-4 space-y-3">
          {navLinks.map((link) => (
            <Link
              key={link.href}
              href={link.href}
              onClick={() => setMobileMenu(false)}
              className="block px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 rounded-lg"
            >
              {link.label}
            </Link>
          ))}
          <div className="pt-3 border-t border-slate-200 flex flex-col gap-2">
            <Link
              href="/problems/create"
              onClick={() => setMobileMenu(false)}
              className="btn-primary w-full text-xs"
            >
              <PlusCircle className="w-4 h-4" />
              <span>Masala joylash</span>
            </Link>
          </div>
        </div>
      )}
    </header>
  );
}
