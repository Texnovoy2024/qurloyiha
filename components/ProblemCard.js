'use client';

import Link from 'next/link';
import { Building2, Tag, Eye, Calendar, ArrowRight, DollarSign } from 'lucide-react';

export default function ProblemCard({ problem }) {
  const statusBadgeClass =
    problem.status === 10
      ? 'badge-open'
      : problem.status === 20
      ? 'badge-review'
      : 'badge-solved';

  const statusText =
    problem.status === 10
      ? 'Ochiq Tanlov'
      : problem.status === 20
      ? 'Ko\'rib chiqilmoqda'
      : 'Yechilgan';

  const formattedBudget = problem.budget
    ? new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 0 }).format(problem.budget)
    : 'Kelishiladi';

  return (
    <div className="clean-card p-6 flex flex-col justify-between h-full group">
      <div className="space-y-4">
        {/* Top Badges */}
        <div className="flex flex-wrap items-center justify-between gap-2">
          <span className="text-xs font-semibold text-sky-700 bg-sky-50 px-3 py-1 rounded-full border border-sky-200/80 flex items-center gap-1.5">
            <Tag className="w-3.5 h-3.5 text-sky-600" />
            <span>{problem.category_name || 'Muhandislik'}</span>
          </span>

          <span className={`badge ${statusBadgeClass}`}>{statusText}</span>
        </div>

        {/* Company Name */}
        <div className="flex items-center gap-2 text-xs font-semibold text-slate-500">
          <Building2 className="w-4 h-4 text-slate-400" />
          <span>{problem.company_name || 'Apex Construction LLC'}</span>
        </div>

        {/* Title */}
        <h3 className="font-bold text-slate-900 text-lg leading-snug group-hover:text-sky-600 transition-colors font-display">
          <Link href={`/problems/${problem.id}`}>
            {problem.title}
          </Link>
        </h3>

        {/* Short Description */}
        <p className="text-xs text-slate-600 line-clamp-3 leading-relaxed">
          {problem.description}
        </p>
      </div>

      {/* Footer Info */}
      <div className="pt-5 mt-6 border-t border-slate-100 flex items-center justify-between">
        <div>
          <span className="text-[11px] font-medium text-slate-400 uppercase tracking-wider block">Ajratilgan Byudjet</span>
          <span className="text-base font-bold text-emerald-600 font-display">
            {formattedBudget}
          </span>
        </div>

        <Link
          href={`/problems/${problem.id}`}
          className="px-4 py-2 rounded-xl bg-slate-50 group-hover:bg-sky-600 text-slate-700 group-hover:text-white text-xs font-semibold flex items-center gap-1.5 transition-all border border-slate-200 group-hover:border-sky-600"
        >
          <span>Batafsil</span>
          <ArrowRight className="w-3.5 h-3.5" />
        </Link>
      </div>
    </div>
  );
}
