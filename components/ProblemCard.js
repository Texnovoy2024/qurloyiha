import Link from 'next/link';
import { Eye, DollarSign, Clock, ArrowRight, Building2, Tag, CheckCircle2 } from 'lucide-react';

export default function ProblemCard({ problem }) {
  const getStatusBadge = (status) => {
    switch (Number(status)) {
      case 10:
        return <span className="badge badge-open">Ochiq Tanlov</span>;
      case 20:
        return <span className="badge badge-review">Ko'rib chiqilmoqda</span>;
      case 30:
        return <span className="badge badge-solved">Yechilgan</span>;
      default:
        return <span className="badge badge-open">Ochiq</span>;
    }
  };

  const formattedBudget = problem.budget
    ? new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 0 }).format(problem.budget)
    : 'Kelishiladi';

  return (
    <div className="glass rounded-2xl p-6 card-hover flex flex-col justify-between relative overflow-hidden group">
      {/* Decorative gradient glow on hover */}
      <div className="absolute -right-12 -top-12 w-32 h-32 bg-cyan-500/10 rounded-full blur-2xl group-hover:bg-cyan-500/25 transition-all pointer-events-none" />

      <div>
        {/* Header Badges */}
        <div className="flex items-center justify-between gap-3 mb-4">
          <span className="text-[11px] font-medium px-2.5 py-1 rounded-md bg-slate-800/80 text-cyan-300 border border-slate-700/80 flex items-center gap-1.5">
            <Tag className="w-3 h-3 text-cyan-400" />
            {problem.category_name || 'Infratuzilma'}
          </span>
          {getStatusBadge(problem.status)}
        </div>

        {/* Company Name */}
        <div className="flex items-center gap-2 text-xs text-slate-400 mb-2">
          <Building2 className="w-3.5 h-3.5 text-slate-500" />
          <span>{problem.company_name || 'Qurilish Korxonasi'}</span>
        </div>

        {/* Title */}
        <h3 className="font-bold text-lg text-white mb-3 line-clamp-2 leading-snug group-hover:text-cyan-300 transition-colors">
          {problem.title}
        </h3>

        {/* Description snippet */}
        <p className="text-slate-400 text-xs line-clamp-3 mb-6 leading-relaxed">
          {problem.description}
        </p>
      </div>

      {/* Footer Info */}
      <div className="pt-4 border-t border-slate-800/80 flex items-center justify-between gap-3 text-xs">
        <div>
          <span className="text-slate-400 block text-[10px] uppercase font-medium">Ajratilgan Byudjet</span>
          <span className="font-bold text-emerald-400 text-base flex items-center gap-0.5">
            {formattedBudget}
          </span>
        </div>

        <Link
          href={`/problems/${problem.id}`}
          className="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-cyan-500/10 hover:bg-cyan-500 text-cyan-400 hover:text-slate-950 font-semibold text-xs transition-all"
        >
          <span>Batafsil</span>
          <ArrowRight className="w-3.5 h-3.5" />
        </Link>
      </div>
    </div>
  );
}
