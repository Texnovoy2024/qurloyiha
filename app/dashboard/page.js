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
    <div className="container py-10 space-y-8">
      {/* Header Banner */}
      <div className="glass rounded-3xl p-6 sm:p-8 border border-slate-800 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
        <div className="absolute right-0 top-0 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none" />

        <div className="flex items-center gap-4 relative z-10">
          <div className="w-14 h-14 rounded-2xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold text-2xl border border-cyan-500/30">
            {currentUser?.username ? currentUser.username[0].toUpperCase() : 'U'}
          </div>
          <div>
            <div className="flex items-center gap-2 mb-1">
              <span className="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-cyan-950 text-cyan-400 border border-cyan-800">
                {roleName}
              </span>
            </div>
            <h1 className="text-2xl font-bold text-white font-display">
              Xush kelibsiz, {currentUser?.username || 'Foydalanuvchi'}!
            </h1>
            <p className="text-xs text-slate-400">Shaxsiy kabinet va loyihalar boshqaruvi</p>
          </div>
        </div>

        <div className="flex items-center gap-3 relative z-10 shrink-0">
          <Link href="/problems/create" className="btn-primary py-3 px-5 text-xs font-bold">
            <PlusCircle className="w-4 h-4" />
            <span>Yangi Masala E'lon Qilish</span>
          </Link>
          <Link href="/profile" className="btn-secondary py-3 px-5 text-xs font-semibold">
            <User className="w-4 h-4 text-cyan-400" />
            <span>Profil</span>
          </Link>
        </div>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div className="glass p-5 rounded-2xl border border-slate-800">
          <div className="text-xs text-slate-400 font-medium mb-1">E'lon Qilingan Masalalar</div>
          <div className="text-2xl font-bold text-white font-display">{myProblems.length}</div>
          <div className="text-[11px] text-cyan-400 mt-1">Tanlovlar aktiv</div>
        </div>
        <div className="glass p-5 rounded-2xl border border-slate-800">
          <div className="text-xs text-slate-400 font-medium mb-1">Tushgan Takliflar</div>
          <div className="text-2xl font-bold text-emerald-400 font-display">{myProposals.length}</div>
          <div className="text-[11px] text-slate-400 mt-1">Ko'rib chiqilmoqda</div>
        </div>
        <div className="glass p-5 rounded-2xl border border-slate-800">
          <div className="text-xs text-slate-400 font-medium mb-1">Muvaffaqiyatli Bitimlar</div>
          <div className="text-2xl font-bold text-indigo-400 font-display">2</div>
          <div className="text-[11px] text-slate-400 mt-1">Shartnoma imzolandi</div>
        </div>
        <div className="glass p-5 rounded-2xl border border-slate-800">
          <div className="text-xs text-slate-400 font-medium mb-1">Reyting / Status</div>
          <div className="text-2xl font-bold text-amber-400 font-display">A+ Verified</div>
          <div className="text-[11px] text-slate-400 mt-1">Tasdiqlangan foydalanuvchi</div>
        </div>
      </div>

      {/* Dashboard Tabs */}
      <div className="space-y-6">
        <div className="flex items-center gap-2 border-b border-slate-800 pb-2">
          <button
            onClick={() => setActiveTab('problems')}
            className={`px-4 py-2.5 text-xs font-bold rounded-xl transition-all ${
              activeTab === 'problems'
                ? 'bg-cyan-500/20 text-cyan-400 border border-cyan-500/30'
                : 'text-slate-400 hover:text-white'
            }`}
          >
            Mening Masalalarim ({myProblems.length})
          </button>
          <button
            onClick={() => setActiveTab('proposals')}
            className={`px-4 py-2.5 text-xs font-bold rounded-xl transition-all ${
              activeTab === 'proposals'
                ? 'bg-cyan-500/20 text-cyan-400 border border-cyan-500/30'
                : 'text-slate-400 hover:text-white'
            }`}
          >
            Yuborilgan Takliflar ({myProposals.length})
          </button>
        </div>

        {/* Tab Content */}
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
              <div key={prop.id} className="glass p-6 rounded-2xl border border-slate-800 space-y-3">
                <div className="flex items-center justify-between">
                  <span className="text-xs font-semibold text-cyan-300">{prop.scientist_name}</span>
                  <span className="badge badge-open">Qabul Qilingan</span>
                </div>
                <h3 className="font-bold text-white text-base">{prop.title}</h3>
                <p className="text-xs text-slate-400">{prop.description}</p>
                <div className="text-xs text-emerald-400 font-bold">Taklif Narxi: ${prop.budget_offer}</div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}
