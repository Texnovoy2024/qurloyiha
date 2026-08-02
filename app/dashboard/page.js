'use client';

import { useState, useEffect } from 'react';
import Link from 'next/link';
import ProblemCard from '@/components/ProblemCard';
import { initialProblems, initialProposals } from '@/lib/mockData';
import {
  LayoutDashboard,
  Building2,
  GraduationCap,
  PlusCircle,
  FileText,
  TrendingUp,
  Award,
  CheckCircle2,
  Clock,
  User
} from 'lucide-react';

export default function DashboardPage() {
  const [currentUser, setCurrentUser] = useState(null);
  const [myProblems, setMyProblems] = useState([]);
  const [myProposals, setMyProposals] = useState([]);
  const [activeTab, setActiveTab] = useState('problems');

  useEffect(() => {
    const savedUser = localStorage.getItem('user');
    if (savedUser) {
      try {
        const u = JSON.parse(savedUser);
        setCurrentUser(u);
      } catch (e) {}
    }

    setMyProblems(initialProblems);
    setMyProposals(initialProposals);
  }, []);

  const roleName = currentUser?.role === 'company'
    ? 'Qurilish Korxonasi'
    : currentUser?.role === 'administrator'
    ? 'Tizim Administratori'
    : 'Olim / Tadqiqotchi';

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
      {/* Header Banner */}
      <div className="clean-card p-6 sm:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden bg-gradient-to-r from-sky-50 to-indigo-50 border-sky-200/80">
        <div className="flex items-center gap-4">
          <div className="w-14 h-14 rounded-2xl bg-sky-600 text-white flex items-center justify-center font-bold text-2xl shadow-md">
            {currentUser?.username ? currentUser.username[0].toUpperCase() : 'U'}
          </div>
          <div>
            <div className="flex items-center gap-2 mb-1">
              <span className="text-xs font-bold px-2.5 py-0.5 rounded-full bg-sky-100 text-sky-800 border border-sky-200">
                {roleName}
              </span>
            </div>
            <h1 className="text-2xl font-bold text-slate-900 font-display">
              Xush kelibsiz, {currentUser?.username || 'Foydalanuvchi'}!
            </h1>
            <p className="text-xs text-slate-600">Shaxsiy kabinet va loyihalar boshqaruvi</p>
          </div>
        </div>

        <div className="flex items-center gap-3 shrink-0">
          <Link href="/problems/create" className="btn-primary py-3 px-5 text-xs font-bold shadow-md">
            <PlusCircle className="w-4 h-4" />
            <span>Yangi Masala E'lon Qilish</span>
          </Link>
          <Link href="/profile" className="btn-secondary py-3 px-5 text-xs font-semibold">
            <User className="w-4 h-4 text-sky-600" />
            <span>Profil</span>
          </Link>
        </div>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div className="clean-card p-5">
          <div className="text-xs text-slate-500 font-semibold mb-1">E'lon Qilingan Masalalar</div>
          <div className="text-3xl font-extrabold text-slate-900 font-display">{myProblems.length}</div>
          <div className="text-[11px] font-semibold text-sky-600 mt-1">Tanlovlar aktiv</div>
        </div>
        <div className="clean-card p-5">
          <div className="text-xs text-slate-500 font-semibold mb-1">Tushgan Takliflar</div>
          <div className="text-3xl font-extrabold text-emerald-600 font-display">{myProposals.length}</div>
          <div className="text-[11px] text-slate-500 mt-1">Ko'rib chiqilmoqda</div>
        </div>
        <div className="clean-card p-5">
          <div className="text-xs text-slate-500 font-semibold mb-1">Muvaffaqiyatli Bitimlar</div>
          <div className="text-3xl font-extrabold text-indigo-600 font-display">2</div>
          <div className="text-[11px] text-slate-500 mt-1">Shartnoma imzolandi</div>
        </div>
        <div className="clean-card p-5">
          <div className="text-xs text-slate-500 font-semibold mb-1">Reyting / Status</div>
          <div className="text-2xl font-extrabold text-amber-600 font-display">A+ Verified</div>
          <div className="text-[11px] text-slate-500 mt-1">Tasdiqlangan foydalanuvchi</div>
        </div>
      </div>

      {/* Tabs */}
      <div className="space-y-6">
        <div className="flex items-center gap-2 border-b border-slate-200 pb-2">
          <button
            onClick={() => setActiveTab('problems')}
            className={`px-4 py-2.5 text-xs font-bold rounded-xl transition-all ${
              activeTab === 'problems'
                ? 'bg-sky-600 text-white shadow-sm'
                : 'text-slate-600 hover:bg-slate-100'
            }`}
          >
            Mening Masalalarim ({myProblems.length})
          </button>
          <button
            onClick={() => setActiveTab('proposals')}
            className={`px-4 py-2.5 text-xs font-bold rounded-xl transition-all ${
              activeTab === 'proposals'
                ? 'bg-sky-600 text-white shadow-sm'
                : 'text-slate-600 hover:bg-slate-100'
            }`}
          >
            Yuborilgan Takliflar ({myProposals.length})
          </button>
        </div>

        {/* Content */}
        {activeTab === 'problems' && (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {myProblems.map(p => (
              <ProblemCard key={p.id} problem={p} />
            ))}
          </div>
        )}

        {activeTab === 'proposals' && (
          <div className="space-y-4">
            {myProposals.map(prop => (
              <div key={prop.id} className="clean-card p-6 space-y-3">
                <div className="flex items-center justify-between">
                  <span className="text-xs font-bold text-sky-700">{prop.scientist_name}</span>
                  <span className="badge badge-open">Qabul Qilingan</span>
                </div>
                <h3 className="font-bold text-slate-900 text-base">{prop.title}</h3>
                <p className="text-xs text-slate-600">{prop.description}</p>
                <div className="text-xs text-emerald-600 font-bold">Taklif Narxi: ${prop.budget_offer}</div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}
