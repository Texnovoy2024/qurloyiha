'use client';

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import { initialCategories } from '@/lib/mockData';
import { Building2, PlusCircle, ArrowLeft, Sparkles, CheckCircle2 } from 'lucide-react';

export default function CreateProblemPage() {
  const router = useRouter();
  const [categories, setCategories] = useState(initialCategories);
  const [formData, setFormData] = useState({
    title: '',
    category_id: '1',
    description: '',
    requirements: '',
    expected_result: '',
    budget: ''
  });
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState('');

  useEffect(() => {
    fetch('/api/v1/categories')
      .then(r => r.json())
      .then(data => { if (data?.data) setCategories(data.data); })
      .catch(() => {});
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setMessage('');

    try {
      const savedUser = localStorage.getItem('user');
      const userObj = savedUser ? JSON.parse(savedUser) : null;

      const res = await fetch('/api/v1/problems', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          ...formData,
          company_id: userObj?.id || 2
        })
      });
      const data = await res.json();

      if (data.success) {
        setMessage('Masalangiz muvaffaqiyatli eʼlon qilindi!');
        setTimeout(() => {
          router.push('/problems');
        }, 1500);
      } else {
        setMessage(data.message || 'Xatolik yuz berdi');
      }
    } catch (err) {
      setMessage('Xatolik: ' + err.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="container py-10 max-w-3xl space-y-8">
      <Link href="/problems" className="inline-flex items-center gap-2 text-xs font-medium text-slate-400 hover:text-cyan-400 transition-colors">
        <ArrowLeft className="w-4 h-4" />
        <span>Katalogga Qaytish</span>
      </Link>

      <div className="glass rounded-3xl p-6 sm:p-10 space-y-8 border border-slate-800">
        <div>
          <span className="text-xs font-semibold text-cyan-400 uppercase tracking-wider block mb-1">Korxonalar Uchun</span>
          <h1 className="text-2xl sm:text-3xl font-extrabold text-white font-display">Yangi Muhandislik Masalasini E'lon Qilish</h1>
          <p className="text-xs text-slate-400 mt-1">Olimlar va mutaxassislarga muammoingizni taqdim eting va eng yaxshi yechimni tanlang.</p>
        </div>

        {message && (
          <div className={`p-4 rounded-2xl text-xs font-medium ${message.includes('muvaffaqiyatli') ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30'}`}>
            {message}
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-6">
          <div className="form-group">
            <label>Masala Sarlavhasi *</label>
            <input
              type="text"
              required
              placeholder="Masalan: Seysmik hududlar uchun yengil va mustahkam beton konstruksiya..."
              value={formData.title}
              onChange={(e) => setFormData({ ...formData, title: e.target.value })}
              className="form-input"
            />
          </div>

          <div className="form-group">
            <label>Soha Kategoriyasi *</label>
            <select
              value={formData.category_id}
              onChange={(e) => setFormData({ ...formData, category_id: e.target.value })}
              className="form-input"
            >
              {categories.map((c) => (
                <option key={c.id} value={c.id}>{c.name_uz}</option>
              ))}
            </select>
          </div>

          <div className="form-group">
            <label>Muammoning Batafsil Tavsifi *</label>
            <textarea
              required
              rows={4}
              placeholder="Obyektdagi mavjud vaziyat, qiyinchiliklar va hal qilinishi kerak bo'lgan muammo..."
              value={formData.description}
              onChange={(e) => setFormData({ ...formData, description: e.target.value })}
              className="form-input"
            />
          </div>

          <div className="form-group">
            <label>Texnik Talablar va Cheklovlar</label>
            <textarea
              rows={3}
              placeholder="Mustahkamlik kuchi, og'irlik, standartlar, kimyoviy tarkib talablari..."
              value={formData.requirements}
              onChange={(e) => setFormData({ ...formData, requirements: e.target.value })}
              className="form-input"
            />
          </div>

          <div className="form-group">
            <label>Kutilayotgan Natija (Deliverables)</label>
            <input
              type="text"
              placeholder="Masalan: Laboratoriya sinov bayonnomasi, namuna va texnologik xarita..."
              value={formData.expected_result}
              onChange={(e) => setFormData({ ...formData, expected_result: e.target.value })}
              className="form-input"
            />
          </div>

          <div className="form-group">
            <label>Ajratilayotgan Byudjet / Grant ($ USD)</label>
            <input
              type="number"
              placeholder="Masalan: 15000"
              value={formData.budget}
              onChange={(e) => setFormData({ ...formData, budget: e.target.value })}
              className="form-input"
            />
          </div>

          <div className="pt-4 flex items-center justify-end gap-4">
            <Link href="/problems" className="btn-secondary py-3 px-6 text-xs">
              Bekor Qilish
            </Link>
            <button type="submit" disabled={loading} className="btn-primary py-3 px-8 text-xs font-bold">
              {loading ? 'Chop etilmoqda...' : 'Masalani E\'lon Qilish'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
