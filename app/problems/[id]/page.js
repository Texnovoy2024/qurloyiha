'use client';

import { useState, useEffect } from 'react';
import { useParams, useRouter } from 'next/navigation';
import Link from 'next/link';
import { initialProblems, initialProposals } from '@/lib/mockData';
import {
  Building2,
  Tag,
  Eye,
  Calendar,
  DollarSign,
  Send,
  CheckCircle2,
  FileText,
  UserCheck,
  ArrowLeft,
  GraduationCap,
  Clock,
  Sparkles
} from 'lucide-react';

export default function ProblemDetailPage() {
  const params = useParams();
  const router = useRouter();
  const problemId = params.id;

  const [problem, setProblem] = useState(null);
  const [proposals, setProposals] = useState([]);
  const [loading, setLoading] = useState(true);

  // Proposal Form State
  const [showModal, setShowModal] = useState(false);
  const [formData, setFormData] = useState({
    title: '',
    description: '',
    solution_details: '',
    budget_offer: '',
    time_offer: ''
  });
  const [submitting, setSubmitting] = useState(false);
  const [message, setMessage] = useState('');

  useEffect(() => {
    async function loadDetail() {
      try {
        const res = await fetch(`/api/v1/problems/${problemId}`);
        const data = await res.json();
        if (data?.data) {
          setProblem(data.data);
          setProposals(data.data.proposals || []);
        } else {
          fallback();
        }
      } catch (e) {
        fallback();
      } finally {
        setLoading(false);
      }
    }

    function fallback() {
      const match = initialProblems.find(p => p.id === Number(problemId)) || initialProblems[0];
      const props = initialProposals.filter(pr => pr.problem_id === Number(problemId));
      setProblem(match);
      setProposals(props);
    }

    if (problemId) loadDetail();
  }, [problemId]);

  const handleSubmitProposal = async (e) => {
    e.preventDefault();
    setSubmitting(true);
    setMessage('');

    try {
      const savedUser = localStorage.getItem('user');
      const userObj = savedUser ? JSON.parse(savedUser) : null;

      const res = await fetch('/api/v1/proposals', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          problem_id: problemId,
          scientist_id: userObj?.id || 3,
          ...formData
        })
      });
      const resData = await res.json();

      if (resData.success) {
        setMessage('Taklifingiz muvaffaqiyatli yuborildi!');
        const newProp = {
          id: Date.now(),
          title: formData.title,
          description: formData.description,
          solution_details: formData.solution_details,
          budget_offer: formData.budget_offer,
          time_offer: formData.time_offer,
          scientist_name: userObj?.username || 'Prof. Alisher Usmanov',
          status: 10,
          created_at: Math.floor(Date.now() / 1000)
        };
        setProposals([newProp, ...proposals]);
        setTimeout(() => {
          setShowModal(false);
          setFormData({ title: '', description: '', solution_details: '', budget_offer: '', time_offer: '' });
          setMessage('');
        }, 1500);
      } else {
        setMessage(resData.message || 'Xatolik yuz berdi');
      }
    } catch (e) {
      setMessage('Xatolik yuz berdi: ' + e.message);
    } finally {
      setSubmitting(false);
    }
  };

  if (loading) {
    return (
      <div className="container py-20 text-center text-slate-400">
        <p className="text-sm">Ma'lumotlar yuklanmoqda...</p>
      </div>
    );
  }

  if (!problem) {
    return (
      <div className="container py-20 text-center text-slate-400">
        <p className="text-sm">Masala topilmadi.</p>
        <Link href="/problems" className="btn-secondary mt-4">Katalogga qaytish</Link>
      </div>
    );
  }

  const formattedBudget = problem.budget
    ? new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 0 }).format(problem.budget)
    : 'Kelishiladi';

  return (
    <div className="container py-10 space-y-10">
      {/* Back Button */}
      <Link href="/problems" className="inline-flex items-center gap-2 text-xs font-medium text-slate-400 hover:text-cyan-400 transition-colors">
        <ArrowLeft className="w-4 h-4" />
        <span>Barcha Masalalarga Qaytish</span>
      </Link>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Main Content Column */}
        <div className="lg:col-span-2 space-y-8">
          {/* Main Card */}
          <div className="glass rounded-3xl p-6 sm:p-8 space-y-6">
            <div className="flex flex-wrap items-center gap-3">
              <span className="badge badge-open">Ochiq Tanlov</span>
              <span className="text-xs font-medium px-3 py-1 rounded-full bg-slate-800 text-cyan-300 border border-slate-700">
                {problem.category_name || 'Muhandislik'}
              </span>
            </div>

            <h1 className="text-2xl sm:text-3xl font-bold text-white font-display leading-tight">
              {problem.title}
            </h1>

            <div className="flex flex-wrap items-center gap-6 text-xs text-slate-400 border-y border-slate-800/80 py-3.5">
              <div className="flex items-center gap-2">
                <Building2 className="w-4 h-4 text-cyan-400" />
                <span className="text-slate-200 font-medium">{problem.company_name || 'Apex Construction LLC'}</span>
              </div>
              <div className="flex items-center gap-2">
                <Eye className="w-4 h-4 text-slate-500" />
                <span>{problem.views_count || 120} ko'rishlar</span>
              </div>
              <div className="flex items-center gap-2">
                <Calendar className="w-4 h-4 text-slate-500" />
                <span>E'lon qilingan: {new Date((problem.created_at || 1784700593) * 1000).toLocaleDateString()}</span>
              </div>
            </div>

            {/* Description */}
            <div className="space-y-3">
              <h3 className="text-sm font-bold text-white uppercase tracking-wider text-cyan-400">Muammo Tavsifi</h3>
              <p className="text-slate-300 text-sm leading-relaxed whitespace-pre-line">
                {problem.description}
              </p>
            </div>

            {/* Requirements */}
            {problem.requirements && (
              <div className="space-y-3 pt-4 border-t border-slate-800">
                <h3 className="text-sm font-bold text-white uppercase tracking-wider text-cyan-400">Texnik Talablar va Cheklovlar</h3>
                <div className="bg-slate-900/80 rounded-2xl p-5 border border-slate-800 text-xs text-slate-300 whitespace-pre-line leading-relaxed">
                  {problem.requirements}
                </div>
              </div>
            )}

            {/* Expected Result */}
            {problem.expected_result && (
              <div className="space-y-3 pt-4 border-t border-slate-800">
                <h3 className="text-sm font-bold text-white uppercase tracking-wider text-emerald-400">Kutilayotgan Natija (Deliverables)</h3>
                <p className="text-slate-300 text-xs leading-relaxed bg-emerald-950/20 p-4 rounded-xl border border-emerald-500/20">
                  {problem.expected_result}
                </p>
              </div>
            )}
          </div>

          {/* Submitted Proposals Section */}
          <div className="glass rounded-3xl p-6 sm:p-8 space-y-6">
            <div className="flex items-center justify-between">
              <h2 className="text-xl font-bold text-white font-display">Taqdim Etilgan Ilmiy Takliflar ({proposals.length})</h2>
              <span className="text-xs text-slate-400">Ekspertizadan o'tkazilmoqda</span>
            </div>

            {proposals.length === 0 ? (
              <p className="text-xs text-slate-400 py-6 text-center">Hozircha ilmiy takliflar yo'q. Birinchi bo'lib taklif yuboring!</p>
            ) : (
              <div className="space-y-4">
                {proposals.map((prop) => (
                  <div key={prop.id} className="bg-slate-900/90 rounded-2xl p-5 border border-slate-800 space-y-3">
                    <div className="flex items-center justify-between gap-3">
                      <div className="flex items-center gap-2 text-xs font-semibold text-cyan-300">
                        <GraduationCap className="w-4 h-4 text-cyan-400" />
                        <span>{prop.scientist_name || 'Olim / Muhandis'}</span>
                      </div>
                      <span className="text-[10px] px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                        Taklif berildi
                      </span>
                    </div>

                    <h4 className="font-bold text-white text-sm">{prop.title}</h4>
                    <p className="text-xs text-slate-300 leading-relaxed">{prop.description}</p>
                    
                    {prop.solution_details && (
                      <div className="text-[11px] text-slate-400 bg-slate-950 p-3 rounded-xl border border-slate-800">
                        <strong className="text-slate-200">Laboratoriya yechimi: </strong>
                        {prop.solution_details}
                      </div>
                    )}

                    <div className="flex items-center gap-4 text-xs text-slate-400 pt-2 border-t border-slate-800/80">
                      {prop.budget_offer && <div>Taklif narxi: <strong className="text-emerald-400">${prop.budget_offer}</strong></div>}
                      {prop.time_offer && <div>Muddati: <strong className="text-slate-200">{prop.time_offer}</strong></div>}
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>
        </div>

        {/* Sidebar Info & CTA */}
        <div className="space-y-6">
          {/* Budget & Action Card */}
          <div className="glass rounded-3xl p-6 border border-slate-800 space-y-6 sticky top-24">
            <div>
              <span className="text-xs text-slate-400 block uppercase font-medium mb-1">Mo'ljallangan Grant / Byudjet</span>
              <div className="text-3xl font-extrabold text-emerald-400 font-display">
                {formattedBudget}
              </div>
            </div>

            <button
              onClick={() => setShowModal(true)}
              className="w-full btn-primary py-3.5 text-xs font-bold rounded-2xl shadow-lg shadow-cyan-500/25 flex items-center justify-center gap-2"
            >
              <Send className="w-4 h-4" />
              <span>Ilmiy Yechim Taklif Qilish</span>
            </button>

            <div className="space-y-3 text-xs text-slate-400 pt-4 border-t border-slate-800">
              <div className="flex items-center gap-2">
                <CheckCircle2 className="w-4 h-4 text-cyan-400 shrink-0" />
                <span>Mualliflik huquqi va patent saqlanadi</span>
              </div>
              <div className="flex items-center gap-2">
                <CheckCircle2 className="w-4 h-4 text-cyan-400 shrink-0" />
                <span>To'g'ridan-to'g'ri shartnoma tuzish imkoniyati</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* SUBMIT PROPOSAL MODAL */}
      {showModal && (
        <div className="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
          <div className="glass max-w-xl w-full rounded-3xl p-6 sm:p-8 space-y-6 border border-slate-800 relative">
            <div className="flex items-center justify-between">
              <h3 className="text-lg font-bold text-white flex items-center gap-2 font-display">
                <Sparkles className="w-5 h-5 text-cyan-400" />
                Ilmiy-Muhandislik Yechimini Taqdim Etish
              </h3>
              <button onClick={() => setShowModal(false)} className="text-slate-400 hover:text-white text-lg">✕</button>
            </div>

            {message && (
              <div className={`p-3 rounded-xl text-xs font-medium ${message.includes('muvaffaqiyatli') ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30'}`}>
                {message}
              </div>
            )}

            <form onSubmit={handleSubmitProposal} className="space-y-4">
              <div className="form-group">
                <label>Taklif Sarlavhasi</label>
                <input
                  type="text"
                  required
                  placeholder="Masalan: Nanobeton va bazalt mikrotolali yechim..."
                  value={formData.title}
                  onChange={(e) => setFormData({ ...formData, title: e.target.value })}
                  className="form-input"
                />
              </div>

              <div className="form-group">
                <label>Qisqacha Izoh va Metodologiya</label>
                <textarea
                  required
                  rows={3}
                  placeholder="Laboratoriya sinovlaringiz va taklif etilayotgan yechimning qisqacha mazmuni..."
                  value={formData.description}
                  onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                  className="form-input"
                />
              </div>

              <div className="form-group">
                <label>Laboratoriya va Texnik Tafsilotlar</label>
                <textarea
                  required
                  rows={3}
                  placeholder="Aralashma nisbatlari, tajriba natijalari va texnologik xarita..."
                  value={formData.solution_details}
                  onChange={(e) => setFormData({ ...formData, solution_details: e.target.value })}
                  className="form-input"
                />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div className="form-group">
                  <label>Taklif Narxi ($ USD)</label>
                  <input
                    type="number"
                    placeholder="Masalan: 12000"
                    value={formData.budget_offer}
                    onChange={(e) => setFormData({ ...formData, budget_offer: e.target.value })}
                    className="form-input"
                  />
                </div>
                <div className="form-group">
                  <label>Bajarish Muddati</label>
                  <input
                    type="text"
                    placeholder="Masalan: 45 kun"
                    value={formData.time_offer}
                    onChange={(e) => setFormData({ ...formData, time_offer: e.target.value })}
                    className="form-input"
                  />
                </div>
              </div>

              <div className="pt-4 flex items-center justify-end gap-3">
                <button type="button" onClick={() => setShowModal(false)} className="btn-secondary py-2.5 px-5 text-xs">
                  Bekor qilish
                </button>
                <button type="submit" disabled={submitting} className="btn-primary py-2.5 px-6 text-xs">
                  {submitting ? 'Yuborilmoqda...' : 'Taklifni Yuborish'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
