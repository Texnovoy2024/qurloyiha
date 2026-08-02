'use client';

import { useState, useEffect, Suspense } from 'react';
import { useSearchParams } from 'next/navigation';
import ProblemCard from '@/components/ProblemCard';
import { initialProblems, initialCategories } from '@/lib/mockData';
import { Search, Filter, Layers, CheckCircle2, ArrowRight } from 'lucide-react';

function ProblemsContent() {
  const searchParams = useSearchParams();
  const initialCategory = searchParams.get('category') || '';
  const initialQuery = searchParams.get('search') || '';

  const [problems, setProblems] = useState(initialProblems);
  const [categories, setCategories] = useState(initialCategories);
  const [selectedCategory, setSelectedCategory] = useState(initialCategory);
  const [selectedStatus, setSelectedStatus] = useState('');
  const [search, setSearch] = useState(initialQuery);

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

  const filteredProblems = problems.filter((p) => {
    const matchCategory = selectedCategory ? String(p.category_id) === String(selectedCategory) : true;
    const matchStatus = selectedStatus ? String(p.status) === String(selectedStatus) : true;
    const matchSearch = search
      ? p.title.toLowerCase().includes(search.toLowerCase()) ||
        p.description.toLowerCase().includes(search.toLowerCase())
      : true;
    return matchCategory && matchStatus && matchSearch;
  });

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
      {/* Header */}
      <div className="space-y-2">
        <span className="text-xs font-bold text-sky-600 uppercase tracking-wider block">Ochiq Tanlovlar Katalogi</span>
        <h1 className="text-3xl font-extrabold text-slate-900 font-display">Muhandislik Masalalari</h1>
        <p className="text-xs text-slate-600 max-w-2xl">
          Qurilish sohasidagi dolzarb ilmiy-texnik muammolarni o'rganing hamda o'z laboratoriya va ilmiy yechimlaringizni taklif eting.
        </p>
      </div>

      {/* Controls / Filter bar */}
      <div className="clean-card p-4 space-y-4">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-3">
          {/* Search bar */}
          <div className="md:col-span-2 relative">
            <Search className="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" />
            <input
              type="text"
              placeholder="Masala nomi yoki kalit so'zlar..."
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className="form-input pl-10 text-xs"
            />
          </div>

          {/* Category filter */}
          <div>
            <select
              value={selectedCategory}
              onChange={(e) => setSelectedCategory(e.target.value)}
              className="form-input text-xs"
            >
              <option value="">Barcha Kategoriyalar</option>
              {categories.map((c) => (
                <option key={c.id} value={c.id}>{c.name_uz}</option>
              ))}
            </select>
          </div>

          {/* Status filter */}
          <div>
            <select
              value={selectedStatus}
              onChange={(e) => setSelectedStatus(e.target.value)}
              className="form-input text-xs"
            >
              <option value="">Barcha Holatlar</option>
              <option value="10">Ochiq Tanlov</option>
              <option value="20">Ko'rib chiqilmoqda</option>
              <option value="30">Yechilgan</option>
            </select>
          </div>
        </div>

        {/* Applied filters bar */}
        {(selectedCategory || selectedStatus || search) && (
          <div className="flex flex-wrap items-center justify-between gap-2 pt-2 border-t border-slate-100 text-xs text-slate-500">
            <div>Topilgan masalalar: <strong className="text-slate-900">{filteredProblems.length} ta</strong></div>
            <button
              onClick={() => { setSelectedCategory(''); setSelectedStatus(''); setSearch(''); }}
              className="text-sky-600 hover:underline font-semibold"
            >
              Filtrlarni tozalash
            </button>
          </div>
        )}
      </div>

      {/* Problem Grid */}
      {filteredProblems.length === 0 ? (
        <div className="clean-card p-12 text-center text-slate-500 space-y-3">
          <p className="text-sm">So'rov bo'yicha hech qanday masala topilmadi.</p>
          <button
            onClick={() => { setSelectedCategory(''); setSelectedStatus(''); setSearch(''); }}
            className="btn-secondary text-xs"
          >
            Filtrlarni tozalash
          </button>
        </div>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {filteredProblems.map((p) => (
            <ProblemCard key={p.id} problem={p} />
          ))}
        </div>
      )}
    </div>
  );
}

export default function ProblemsPage() {
  return (
    <Suspense fallback={<div className="text-xs text-slate-500 text-center py-20">Yuklanmoqda...</div>}>
      <ProblemsContent />
    </Suspense>
  );
}
