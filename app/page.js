'use client';

import { useState, useEffect } from 'react';
import Link from 'next/link';
import ProblemCard from '@/components/ProblemCard';
import { initialCategories, initialProblems } from '@/lib/mockData';
import {
  Sparkles,
  Search,
  Building2,
  GraduationCap,
  Award,
  ArrowRight,
  TrendingUp,
  ShieldCheck,
  Zap,
  CheckCircle2,
  Cpu,
  Layers,
  FileCheck
} from 'lucide-react';

export default function HomePage() {
  const [categories, setCategories] = useState(initialCategories);
  const [problems, setProblems] = useState(initialProblems);
  const [selectedCategory, setSelectedCategory] = useState(null);
  const [searchQuery, setSearchQuery] = useState('');
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    async function fetchData() {
      try {
        const [catRes, probRes] = await Promise.all([
          fetch('/api/v1/categories').then(r => r.json()).catch(() => ({ data: initialCategories })),
          fetch('/api/v1/problems').then(r => r.json()).catch(() => ({ data: initialProblems }))
        ]);

        if (catRes?.data) setCategories(catRes.data);
        if (probRes?.data) setProblems(probRes.data);
      } catch (e) {
        console.warn('Using initial state');
      } finally {
        setLoading(false);
      }
    }
    fetchData();
  }, []);

  const filteredProblems = problems.filter(p => {
    const matchesCategory = selectedCategory ? Number(p.category_id) === Number(selectedCategory) : true;
    const matchesSearch = searchQuery
      ? p.title.toLowerCase().includes(searchQuery.toLowerCase()) || p.description.toLowerCase().includes(searchQuery.toLowerCase())
      : true;
    return matchesCategory && matchesSearch;
  });

  return (
    <div className="space-y-24 pb-12">
      {/* HERO SECTION */}
      <section className="relative pt-16 pb-20 overflow-hidden border-b border-slate-900">
        {/* Glowing Background Orbs */}
        <div className="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[350px] bg-gradient-to-r from-cyan-600/20 via-indigo-600/15 to-purple-600/20 rounded-full blur-3xl pointer-events-none" />
        <div className="absolute top-10 right-10 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none" />

        <div className="container relative z-10 text-center max-w-4xl mx-auto space-y-8">
          {/* Top Pill Tag */}
          <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-900/90 border border-slate-800 text-xs font-semibold text-cyan-400 shadow-xl">
            <Sparkles className="w-4 h-4 text-amber-400" />
            <span>Oʻzbekiston Qurilish Innovatsiyalari Portali</span>
          </div>

          {/* Main Title */}
          <h1 className="text-4xl sm:text-6xl font-extrabold text-white tracking-tight leading-[1.15] font-display">
            Qurilish Sanoati va <br className="hidden sm:inline" />
            <span className="text-gradient">Ilmiy Innovatsiyalar Ko'prigi</span>
          </h1>

          {/* Subtitle */}
          <p className="text-base sm:text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed">
            Qurilish kompaniyalari oʻz texnik muammolarini joylaydi, Oʻzbekiston va xalqaro yetakchi olimlar esa eng maqbul ilmiy-muhandislik yechimlarini taqdim etishadi.
          </p>

          {/* Search & Action Buttons */}
          <div className="pt-2 flex flex-col sm:flex-row items-center justify-center gap-4 max-w-xl mx-auto">
            <div className="relative w-full">
              <Search className="w-5 h-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" />
              <input
                type="text"
                placeholder="Muhandislik masalasi yoki texnologiya qidirish..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="w-full pl-12 pr-4 py-3.5 rounded-2xl bg-slate-900/90 border border-slate-800 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all shadow-inner"
              />
            </div>
            <Link
              href="/problems"
              className="w-full sm:w-auto btn-primary whitespace-nowrap py-3.5 px-6 rounded-2xl"
            >
              <span>Barcha Masalalar</span>
              <ArrowRight className="w-4 h-4" />
            </Link>
          </div>

          {/* Platform Metrics */}
          <div className="pt-10 grid grid-cols-2 md:grid-cols-4 gap-4 text-left">
            <div className="glass p-5 rounded-2xl border border-slate-800">
              <div className="text-xs text-slate-400 font-medium mb-1">Jami Masalalar</div>
              <div className="text-2xl font-bold text-white font-display">140+</div>
              <div className="text-[11px] text-emerald-400 mt-1 flex items-center gap-1">
                <TrendingUp className="w-3 h-3" />
                <span>+12 ushbu oyda</span>
              </div>
            </div>
            <div className="glass p-5 rounded-2xl border border-slate-800">
              <div className="text-xs text-slate-400 font-medium mb-1">Umumiy Byudjet</div>
              <div className="text-2xl font-bold text-emerald-400 font-display">$450,000+</div>
              <div className="text-[11px] text-slate-400 mt-1">Gʻoliblarga ajratilgan</div>
            </div>
            <div className="glass p-5 rounded-2xl border border-slate-800">
              <div className="text-xs text-slate-400 font-medium mb-1">Ekspert Olimlar</div>
              <div className="text-2xl font-bold text-cyan-400 font-display">320+</div>
              <div className="text-[11px] text-slate-400 mt-1">DSc & PhD darajasidagi</div>
            </div>
            <div className="glass p-5 rounded-2xl border border-slate-800">
              <div className="text-xs text-slate-400 font-medium mb-1">Yechilgan Loyihalar</div>
              <div className="text-2xl font-bold text-indigo-400 font-display">98%</div>
              <div className="text-[11px] text-slate-400 mt-1">Muvaffaqiyat ko'rsatkichi</div>
            </div>
          </div>
        </div>
      </section>

      {/* CATEGORIES SECTION */}
      <section className="container">
        <div className="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
          <div>
            <span className="text-xs font-semibold uppercase tracking-wider text-cyan-400 block mb-1">Yo'nalishlar Bo'yicha</span>
            <h2 className="text-2xl sm:text-3xl font-bold text-white">Soha Kategoriyalari</h2>
          </div>
          <button
            onClick={() => setSelectedCategory(null)}
            className={`text-xs font-medium px-4 py-2 rounded-xl transition-all ${
              selectedCategory === null ? 'bg-cyan-500 text-slate-950 font-semibold' : 'bg-slate-900 text-slate-400 hover:text-white'
            }`}
          >
            Barchasi ({problems.length})
          </button>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
          {categories.map((cat) => {
            const isSelected = selectedCategory === cat.id;
            return (
              <button
                key={cat.id}
                onClick={() => setSelectedCategory(isSelected ? null : cat.id)}
                className={`glass p-5 rounded-2xl text-left transition-all flex flex-col justify-between h-36 ${
                  isSelected
                    ? 'border-cyan-500 bg-cyan-950/30 ring-2 ring-cyan-500/20'
                    : 'hover:border-slate-700 hover:bg-slate-900/60'
                }`}
              >
                <div className="w-10 h-10 rounded-xl bg-slate-800/80 flex items-center justify-center text-cyan-400 mb-3">
                  <Layers className="w-5 h-5" />
                </div>
                <div>
                  <h3 className="font-semibold text-sm text-white line-clamp-1">{cat.name_uz}</h3>
                  <p className="text-[11px] text-slate-400 line-clamp-1 mt-0.5">{cat.description}</p>
                </div>
              </button>
            );
          })}
        </div>
      </section>

      {/* FEATURED PROBLEMS GRID */}
      <section className="container">
        <div className="flex items-center justify-between mb-8">
          <div>
            <span className="text-xs font-semibold uppercase tracking-wider text-emerald-400 block mb-1">Dolzarb Masalalar</span>
            <h2 className="text-2xl sm:text-3xl font-bold text-white">Ochiq Muhandislik Tanlovlari</h2>
          </div>
          <Link
            href="/problems"
            className="text-xs font-semibold text-cyan-400 hover:text-cyan-300 flex items-center gap-1"
          >
            <span>Barcha masalalarni ko'rish</span>
            <ArrowRight className="w-3.5 h-3.5" />
          </Link>
        </div>

        {filteredProblems.length === 0 ? (
          <div className="glass rounded-3xl p-12 text-center text-slate-400">
            <p className="text-sm">Ushbu mezon bo'yicha hech qanday masala topilmadi.</p>
          </div>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {filteredProblems.map((problem) => (
              <ProblemCard key={problem.id} problem={problem} />
            ))}
          </div>
        )}
      </section>

      {/* HOW IT WORKS SECTION */}
      <section className="container">
        <div className="glass rounded-3xl p-8 sm:p-12 border border-slate-800 relative overflow-hidden">
          <div className="text-center max-w-2xl mx-auto mb-12 space-y-2">
            <span className="text-xs font-semibold uppercase tracking-wider text-cyan-400">Tizim Qanday Ishlaydi</span>
            <h2 className="text-2xl sm:text-3xl font-bold text-white">3 Qadamda Muhandislik Yechimi</h2>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
            {/* Step 1 */}
            <div className="space-y-4">
              <div className="w-12 h-12 rounded-2xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center font-bold text-lg border border-cyan-500/20">
                01
              </div>
              <h3 className="text-lg font-bold text-white">1. Masalani E'lon Qilish</h3>
              <p className="text-xs text-slate-400 leading-relaxed">
                Qurilish korxonasi oʻz obyektidagi texnik qiyinchilik, material taqchilligi yoki seysmik talablarni batafsil va byudjet ko'rsatgan holda e'lon qiladi.
              </p>
            </div>

            {/* Step 2 */}
            <div className="space-y-4">
              <div className="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center font-bold text-lg border border-indigo-500/20">
                02
              </div>
              <h3 className="text-lg font-bold text-white">2. Olimlar Taklifi</h3>
              <p className="text-xs text-slate-400 leading-relaxed">
                Respublika ilmiy institutlari va universitet olimlari laboratoriya sinovlari va hisob-kitoblarga asoslangan innovatsion takliflarini yuborishadi.
              </p>
            </div>

            {/* Step 3 */}
            <div className="space-y-4">
              <div className="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center font-bold text-lg border border-emerald-500/20">
                03
              </div>
              <h3 className="text-lg font-bold text-white">3. Shartnoma va Tatbiq</h3>
              <p className="text-xs text-slate-400 leading-relaxed">
                Korxona eng maqbul ilmiy yechimni tanlaydi, mualliflik huquqi va shartnoma rasmiylashtirilib, amaliyotga joriy etiladi.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* CTA SECTION */}
      <section className="container">
        <div className="rounded-3xl bg-gradient-to-r from-cyan-900/40 via-indigo-900/40 to-slate-900 p-8 sm:p-14 border border-cyan-500/20 flex flex-col md:flex-row items-center justify-between gap-8">
          <div className="space-y-3 max-w-xl">
            <h2 className="text-2xl sm:text-4xl font-bold text-white">Qurilishda Yangi Innovatsiyalar Yarating</h2>
            <p className="text-xs sm:text-sm text-slate-300 leading-relaxed">
              Bizning platformamiz orqali ilmiy salohiyatingizni biznes bilan bog'lang yoki korxonangiz uchun eng ilg'or texnologiyalarni toping.
            </p>
          </div>
          <div className="flex flex-col sm:flex-row gap-3 shrink-0">
            <Link href="/signup?role=company" className="btn-primary py-3 px-6 text-xs">
              <Building2 className="w-4 h-4" />
              <span>Korxona Sifatida Ro'yxatdan O'tish</span>
            </Link>
            <Link href="/signup?role=scientist" className="btn-secondary py-3 px-6 text-xs">
              <GraduationCap className="w-4 h-4 text-cyan-400" />
              <span>Olim Sifatida Qo'shilish</span>
            </Link>
          </div>
        </div>
      </section>
    </div>
  );
}
