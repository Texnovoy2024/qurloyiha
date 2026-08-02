'use client';

import { useState, useEffect } from 'react';
import Link from 'next/link';
import ProblemCard from '@/components/ProblemCard';
import { initialCategories, initialProblems } from '@/lib/mockData';
import { Search, PlusCircle, Filter, SlidersHorizontal, Tag } from 'lucide-react';

export default function ProblemsPage() {
  const [categories, setCategories] = useState(initialCategories);
  const [problems, setProblems] = useState(initialProblems);
  const [selectedCategory, setSelectedCategory] = useState('');
  const [selectedStatus, setSelectedStatus] = useState('');
  const [searchQuery, setSearchQuery] = useState('');
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    async function loadData() {
      try {
        const [catRes, probRes] = await Promise.all([
          fetch('/api/v1/categories').then(r => r.json()).catch(() => ({ data: initialCategories })),
          fetch('/api/v1/problems').then(r => r.json()).catch(() => ({ data: initialProblems }))
        ]);
        if (catRes?.data) setCategories(catRes.data);
        if (probRes?.data) setProblems(probRes.data);
      } catch (e) {
        console.warn('Using fallback data');
      } finally {
        setLoading(false);
      }
    }
    loadData();
  }, []);

  const filteredProblems = problems.filter(p => {
    const matchesCategory = selectedCategory ? Number(p.category_id) === Number(selectedCategory) : true;
    const matchesStatus = selectedStatus ? Number(p.status) === Number(selectedStatus) : true;
    const matchesSearch = searchQuery
      ? p.title.toLowerCase().includes(searchQuery.toLowerCase()) || p.description.toLowerCase().includes(searchQuery.toLowerCase())
      : true;
    return matchesCategory && matchesStatus && matchesSearch;
  });

  return (
    <div className="container py-10 space-y-8">
      {/* Header */}
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-800 pb-6">
        <div>
          <span className="text-xs font-semibold text-cyan-400 uppercase tracking-wider block mb-1">Muhandislik Masalalari Katalogi</span>
          <h1 className="text-3xl font-extrabold text-white font-display">Barcha E'lon Qilingan Muammolar</h1>
          <p className="text-xs text-slate-400 mt-1">Sanoat va qurilish sohasidagi innovatsion ilmiy tanlovlar</p>
        </div>

        <Link href="/problems/create" className="btn-primary shrink-0 text-xs py-3 px-5">
          <PlusCircle className="w-4 h-4" />
          <span>Yangi Masala E'lon Qilish</span>
        </Link>
      </div>

      {/* Search & Filter Bar */}
      <div className="glass p-5 rounded-2xl border border-slate-800 grid grid-cols-1 md:grid-cols-4 gap-4">
        {/* Search */}
        <div className="md:col-span-2 relative">
          <Search className="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input
            type="text"
            placeholder="Kalit so'z bo'yicha qidiruv..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500"
          />
        </div>

        {/* Category Select */}
        <div>
          <select
            value={selectedCategory}
            onChange={(e) => setSelectedCategory(e.target.value)}
            className="w-full px-3 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-xs text-slate-200 focus:outline-none focus:border-cyan-500"
          >
            <option value="">Barcha Kategoriyalar</option>
            {categories.map((c) => (
              <option key={c.id} value={c.id}>{c.name_uz}</option>
            ))}
          </select>
        </div>

        {/* Status Select */}
        <div>
          <select
            value={selectedStatus}
            onChange={(e) => setSelectedStatus(e.target.value)}
            className="w-full px-3 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-xs text-slate-200 focus:outline-none focus:border-cyan-500"
          >
            <option value="">Barcha Holatlar</option>
            <option value="10">Ochiq Tanlov</option>
            <option value="20">Ko'rib chiqilmoqda</option>
            <option value="30">Yechilgan</option>
          </select>
        </div>
      </div>

      {/* Problem Grid */}
      {filteredProblems.length === 0 ? (
        <div className="glass rounded-3xl p-12 text-center text-slate-400">
          <p className="text-sm">Tanlangan filtr bo'yicha masalalar topilmadi.</p>
        </div>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {filteredProblems.map((problem) => (
            <ProblemCard key={problem.id} problem={problem} />
          ))}
        </div>
      )}
    </div>
  );
}
