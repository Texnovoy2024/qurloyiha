'use client';

import { useState, useEffect } from 'react';
import Link from 'next/link';
import ProblemCard from '@/components/ProblemCard';
import { initialProblems, initialCategories } from '@/lib/mockData';
import {
  Building2,
  GraduationCap,
  PlusCircle,
  Search,
  ArrowRight,
  TrendingUp,
  Award,
  Layers,
  CheckCircle2,
  Sparkles,
  Zap,
  ShieldCheck,
  Cpu
} from 'lucide-react';

export default function HomePage() {
  const [problems, setProblems] = useState(initialProblems);
  const [categories, setCategories] = useState(initialCategories);
  const [searchTerm, setSearchTerm] = useState('');

  useEffect(() => {
    fetch('/api/v1/problems')
      .then((res) => res.json())
      .then((data) => {
        if (data?.data?.length > 0) setProblems(data.data);
      })
      .catch(() => {});

    fetch('/api/v1/categories')
      .then((res) => res.json())
      .then((data) => {
        if (data?.data?.length > 0) setCategories(data.data);
      })
      .catch(() => {});
  }, []);

  const featuredProblems = problems.slice(0, 6);

  return (
    <div className="space-y-16 pb-10">
      {/* Hero Section */}
      <section className="relative overflow-hidden bg-gradient-to-b from-sky-50/60 via-slate-50 to-f8fafc border-b border-slate-200/80 pt-12 pb-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-8">
          <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-sky-100 text-sky-800 border border-sky-200 text-xs font-semibold shadow-xs">
            <Sparkles className="w-4 h-4 text-sky-600" />
            <span>O'zbekiston Qurilish Innovatsiyalari Portali</span>
          </div>

          <h1 className="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight max-w-4xl mx-auto leading-[1.15] font-display">
            Qurilish Sanoati va <br className="hidden sm:inline" />
            <span className="text-sky-600">Ilmiy Innovatsiyalar</span> Ko'prigi
          </h1>

          <p className="text-slate-600 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
            Qurilish kompaniyalari o'z texnik muammolarini joylaydi, O'zbekiston va xalqaro yetakchi olimlar esa eng maqbul ilmiy-muhandislik yechimlarini taqdim etishadi.
          </p>

          {/* Quick Search */}
          <div className="max-w-2xl mx-auto bg-white p-2 rounded-2xl border border-slate-300 shadow-lg shadow-slate-200/60 flex items-center gap-2">
            <div className="flex-1 flex items-center gap-3 px-3">
              <Search className="w-5 h-5 text-slate-400" />
              <input
                type="text"
                placeholder="Muhandislik masalasi yoki texnologiya qidirish..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                className="w-full bg-transparent border-none outline-none text-xs sm:text-sm text-slate-900 placeholder:text-slate-400"
              />
            </div>
            <Link
              href={`/problems?search=${encodeURIComponent(searchTerm)}`}
              className="px-5 py-3 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-semibold text-xs flex items-center gap-2 transition-all shadow-md shadow-sky-600/20"
            >
              <span>Qidirish</span>
              <ArrowRight className="w-4 h-4" />
            </Link>
          </div>

          {/* Key Metrics */}
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto pt-8">
            <div className="clean-card p-5 text-left">
              <div className="text-xs font-semibold text-slate-500 mb-1">Jami Masalalar</div>
              <div className="text-3xl font-extrabold text-slate-900 font-display">140+</div>
              <div className="text-[11px] font-semibold text-emerald-600 mt-1 flex items-center gap-1">
                <TrendingUp className="w-3.5 h-3.5" /> +12 ushbu oyda
              </div>
            </div>

            <div className="clean-card p-5 text-left">
              <div className="text-xs font-semibold text-slate-500 mb-1">Umumiy Byudjet</div>
              <div className="text-3xl font-extrabold text-sky-600 font-display">$450,000+</div>
              <div className="text-[11px] text-slate-500 mt-1">G'oliblarga ajratilgan</div>
            </div>

            <div className="clean-card p-5 text-left">
              <div className="text-xs font-semibold text-slate-500 mb-1">Ekspert Olimlar</div>
              <div className="text-3xl font-extrabold text-slate-900 font-display">320+</div>
              <div className="text-[11px] text-slate-500 mt-1">DSc & PhD darajasidagi</div>
            </div>

            <div className="clean-card p-5 text-left">
              <div className="text-xs font-semibold text-slate-500 mb-1">Yechilgan Loyihalar</div>
              <div className="text-3xl font-extrabold text-emerald-600 font-display">98%</div>
              <div className="text-[11px] text-slate-500 mt-1">Muvaffaqiyat ko'rsatkichi</div>
            </div>
          </div>
        </div>
      </section>

      {/* Categories Grid */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div className="flex items-center justify-between">
          <div>
            <span className="text-xs font-bold text-sky-600 uppercase tracking-wider block mb-1">Yo'nalishlar Bo'yicha</span>
            <h2 className="text-2xl sm:text-3xl font-bold text-slate-900 font-display">Soha Kategoriyalari</h2>
          </div>
          <Link href="/problems" className="text-xs font-semibold text-sky-600 hover:text-sky-700 flex items-center gap-1">
            <span>Barchasi ({categories.length})</span>
            <ArrowRight className="w-4 h-4" />
          </Link>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          {categories.map((cat) => (
            <Link
              key={cat.id}
              href={`/problems?category=${cat.id}`}
              className="clean-card p-6 flex items-start gap-4 group"
            >
              <div className="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 border border-sky-100 group-hover:bg-sky-600 group-hover:text-white transition-all">
                <Layers className="w-6 h-6" />
              </div>
              <div className="space-y-1">
                <h3 className="font-bold text-slate-900 text-base group-hover:text-sky-600 transition-colors">
                  {cat.name_uz}
                </h3>
                <p className="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                  {cat.description_uz}
                </p>
              </div>
            </Link>
          ))}
        </div>
      </section>

      {/* Featured Problems Section */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div className="flex items-center justify-between">
          <div>
            <span className="text-xs font-bold text-sky-600 uppercase tracking-wider block mb-1">Dolzarb Masalalar</span>
            <h2 className="text-2xl sm:text-3xl font-bold text-slate-900 font-display">Ochiq Muhandislik Tanlovlari</h2>
          </div>
          <Link href="/problems" className="btn-secondary py-2.5 px-4 text-xs font-semibold">
            <span>Barcha masalalarni ko'rish</span>
            <ArrowRight className="w-4 h-4" />
          </Link>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {featuredProblems.map((problem) => (
            <ProblemCard key={problem.id} problem={problem} />
          ))}
        </div>
      </section>

      {/* Workflow Section */}
      <section className="bg-slate-900 text-white py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
          <div className="text-center space-y-3 max-w-2xl mx-auto">
            <span className="text-xs font-bold text-sky-400 uppercase tracking-wider">Tizim Qanday Ishlaydi</span>
            <h2 className="text-3xl font-bold font-display">3 Qadamda Muhandislik Yechimi</h2>
            <p className="text-xs text-slate-400">Bizning ekotizimimiz orqali ilmiy loyihalarni amaliyotga tatbiq etish mexanizmi</p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div className="bg-slate-800/80 p-6 rounded-2xl border border-slate-700 space-y-4">
              <div className="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center font-bold text-lg border border-sky-500/30">
                01
              </div>
              <h3 className="font-bold text-lg text-white">1. Masalani E'lon Qilish</h3>
              <p className="text-xs text-slate-300 leading-relaxed">
                Qurilish korxonasi o'z obyektdagi teknik qiyinchilik, material taqchilligi yoki seysmik talablarni batafsil va byudjet ko'rsatgan holda e'lon qiladi.
              </p>
            </div>

            <div className="bg-slate-800/80 p-6 rounded-2xl border border-slate-700 space-y-4">
              <div className="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center font-bold text-lg border border-sky-500/30">
                02
              </div>
              <h3 className="font-bold text-lg text-white">2. Olimlar Taklifi</h3>
              <p className="text-xs text-slate-300 leading-relaxed">
                Respublika ilmiy institutlari va universitet olimlari laboratoriya sinovlari va hisob-kitoblarga asoslangan innovatsion takliflarini yuborishadi.
              </p>
            </div>

            <div className="bg-slate-800/80 p-6 rounded-2xl border border-slate-700 space-y-4">
              <div className="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-lg border border-emerald-500/30">
                03
              </div>
              <h3 className="font-bold text-lg text-white">3. Shartnoma va Tatbiq</h3>
              <p className="text-xs text-slate-300 leading-relaxed">
                Korxona eng maqbul ilmiy yechimni tanlaydi, mualliflik huquqi va shartnoma rasmiylashtirilib, amaliyotga joriy etiladi.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* CTA Banner */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="clean-card bg-gradient-to-r from-sky-600 to-indigo-600 text-white p-8 sm:p-12 rounded-3xl flex flex-col md:flex-row items-center justify-between gap-8 shadow-xl shadow-sky-600/20 border-none">
          <div className="space-y-3 text-center md:text-left">
            <h2 className="text-2xl sm:text-3xl font-extrabold font-display">Qurilishda Yangi Innovatsiyalar Yarating</h2>
            <p className="text-sky-100 text-xs sm:text-sm max-w-xl">
              Bizning platformamiz orqali ilmiy salohiyatingizni biznes bilan bog'lang yoki korxonangiz uchun eng ilg'or texnologiyalarni toping.
            </p>
          </div>

          <div className="flex flex-wrap items-center justify-center gap-4 shrink-0">
            <Link href="/signup?role=company" className="px-6 py-3.5 rounded-xl bg-white text-slate-900 text-xs font-bold hover:bg-slate-100 transition-all shadow-md">
              Korxona Sifatida Ro'yxatdan O'tish
            </Link>
            <Link href="/signup?role=scientist" className="px-6 py-3.5 rounded-xl bg-sky-950/60 text-white text-xs font-bold hover:bg-sky-950 transition-all border border-white/20">
              Olim Sifatida Qo'shilish
            </Link>
          </div>
        </div>
      </section>
    </div>
  );
}
