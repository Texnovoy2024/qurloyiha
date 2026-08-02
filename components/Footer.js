import Link from 'next/link';
import { Building2, ShieldCheck, Mail, Phone, MapPin, Sparkles } from 'lucide-react';

export default function Footer() {
  return (
    <footer className="bg-slate-900 text-slate-300 pt-16 pb-12 mt-20 border-t border-slate-800">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-10">
          {/* Brand Info */}
          <div className="space-y-4 md:col-span-1">
            <Link href="/" className="flex items-center gap-3">
              <div className="w-10 h-10 rounded-xl bg-sky-500 flex items-center justify-center text-slate-950 font-bold shadow-md">
                <Building2 className="w-5 h-5" />
              </div>
              <span className="font-extrabold text-xl text-white font-display">QurilishLoyiha.uz</span>
            </Link>
            <p className="text-xs text-slate-400 leading-relaxed">
              Qurilish korxonalari va ilmiy-tadqiqot institutlarini birlashtiruvchi, innovatsion muhandislik yechimlarini tahlil qiluvchi yagona platforma.
            </p>
            <div className="flex items-center gap-2 text-xs font-semibold text-emerald-400 bg-emerald-950/40 p-2.5 rounded-xl border border-emerald-800/40">
              <ShieldCheck className="w-4 h-4" />
              <span>Davlat standartlariga moslashtirilgan</span>
            </div>
          </div>

          {/* Categories */}
          <div className="space-y-3">
            <h4 className="text-xs font-bold text-white uppercase tracking-wider">Yo'nalishlar & Kategoriyalar</h4>
            <ul className="space-y-2 text-xs text-slate-400">
              <li><Link href="/problems" className="hover:text-sky-400 transition-colors">Muhandislik va Konstruksiyalar</Link></li>
              <li><Link href="/problems" className="hover:text-sky-400 transition-colors">Yashil Qurilish va Materiallar</Link></li>
              <li><Link href="/problems" className="hover:text-sky-400 transition-colors">Infratuzilma va Transport</Link></li>
              <li><Link href="/problems" className="hover:text-sky-400 transition-colors">Geotexnika Muhandisligi</Link></li>
              <li><Link href="/problems" className="hover:text-sky-400 transition-colors">Aqlli Qurilish va IoT</Link></li>
            </ul>
          </div>

          {/* Links */}
          <div className="space-y-3">
            <h4 className="text-xs font-bold text-white uppercase tracking-wider">Foydali Havolalar</h4>
            <ul className="space-y-2 text-xs text-slate-400">
              <li><Link href="/problems" className="hover:text-sky-400 transition-colors">Barcha Muammolar Ro'yxati</Link></li>
              <li><Link href="/problems/create" className="hover:text-sky-400 transition-colors">Yangi Masala E'lon Qilish</Link></li>
              <li><Link href="/signup?role=scientist" className="hover:text-sky-400 transition-colors">Olimlar uchun Ro'yxatdan o'tish</Link></li>
              <li><Link href="/signup?role=company" className="hover:text-sky-400 transition-colors">Korxonalar uchun Ro'yxatdan o'tish</Link></li>
            </ul>
          </div>

          {/* Contacts */}
          <div className="space-y-3">
            <h4 className="text-xs font-bold text-white uppercase tracking-wider">Bog'lanish</h4>
            <ul className="space-y-2.5 text-xs text-slate-400">
              <li className="flex items-start gap-2.5">
                <MapPin className="w-4 h-4 text-sky-400 shrink-0 mt-0.5" />
                <span>Toshkent sh., Amir Temur shox ko'chasi 100</span>
              </li>
              <li className="flex items-center gap-2.5">
                <Phone className="w-4 h-4 text-sky-400 shrink-0" />
                <span>+998 (71) 123-45-67</span>
              </li>
              <li className="flex items-center gap-2.5">
                <Mail className="w-4 h-4 text-sky-400 shrink-0" />
                <span>info@qurishloyiha.uz</span>
              </li>
            </ul>
          </div>
        </div>

        <div className="pt-8 border-t border-slate-800 text-center md:flex md:items-center md:justify-between text-xs text-slate-500">
          <p>© 2026 QurilishLoyiha.uz — Barcha huquqlar himoyalangan.</p>
          <p className="mt-2 md:mt-0">Yaratildi: Next.js + React bilan</p>
        </div>
      </div>
    </footer>
  );
}
