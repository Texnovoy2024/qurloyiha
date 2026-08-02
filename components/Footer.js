import Link from 'next/link';
import { Building2, Globe, ShieldCheck, Mail, Phone, MapPin, Heart } from 'lucide-react';

export default function Footer() {
  return (
    <footer className="bg-slate-950 border-t border-slate-900 pt-16 pb-12 mt-20 text-slate-400 text-sm">
      <div className="container grid grid-cols-1 md:grid-cols-4 gap-10">
        {/* Col 1 */}
        <div className="space-y-4">
          <div className="flex items-center gap-3">
            <div className="w-9 h-9 rounded-lg bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold">
              <Building2 className="w-5 h-5" />
            </div>
            <span className="font-bold text-lg text-white font-display">QurilishLoyiha.uz</span>
          </div>
          <p className="text-xs text-slate-400 leading-relaxed">
            Qurilish korxonalari va ilmiy-tadqiqot institutlarini birlashtiruvchi, innovatsion muhandislik yechimlarini tahlil qiluvchi yagona platforma.
          </p>
          <div className="flex items-center gap-2 text-xs text-cyan-400">
            <ShieldCheck className="w-4 h-4" />
            <span>Davlat standartlariga moslashtirilgan</span>
          </div>
        </div>

        {/* Col 2 */}
        <div>
          <h4 className="font-semibold text-white mb-4 text-xs uppercase tracking-wider">Yo'nalishlar & Kategoriyalar</h4>
          <ul className="space-y-2.5 text-xs">
            <li><Link href="/problems?category=1" className="hover:text-cyan-400 transition-colors">Muhandislik va Konstruksiyalar</Link></li>
            <li><Link href="/problems?category=2" className="hover:text-cyan-400 transition-colors">Yashil Qurilish va Materiallar</Link></li>
            <li><Link href="/problems?category=3" className="hover:text-cyan-400 transition-colors">Infratuzilma va Transport</Link></li>
            <li><Link href="/problems?category=4" className="hover:text-cyan-400 transition-colors">Geotexnika Muhandisligi</Link></li>
            <li><Link href="/problems?category=5" className="hover:text-cyan-400 transition-colors">Aqlli Qurilish va IoT</Link></li>
          </ul>
        </div>

        {/* Col 3 */}
        <div>
          <h4 className="font-semibold text-white mb-4 text-xs uppercase tracking-wider">Foydali Havolalar</h4>
          <ul className="space-y-2.5 text-xs">
            <li><Link href="/problems" className="hover:text-cyan-400 transition-colors">Barcha Muammolar Ro'yxati</Link></li>
            <li><Link href="/problems/create" className="hover:text-cyan-400 transition-colors">Yangi Masala E'lon Qilish</Link></li>
            <li><Link href="/signup?role=scientist" className="hover:text-cyan-400 transition-colors">Olimlar uchun Ro'yxatdan o'tish</Link></li>
            <li><Link href="/signup?role=company" className="hover:text-cyan-400 transition-colors">Korxonalar uchun Ro'yxatdan o'tish</Link></li>
          </ul>
        </div>

        {/* Col 4 */}
        <div>
          <h4 className="font-semibold text-white mb-4 text-xs uppercase tracking-wider">Bog'lanish</h4>
          <div className="space-y-3 text-xs">
            <div className="flex items-center gap-2.5">
              <MapPin className="w-4 h-4 text-cyan-400 shrink-0" />
              <span>Toshkent sh., Amir Temur shox ko'chasi 100</span>
            </div>
            <div className="flex items-center gap-2.5">
              <Phone className="w-4 h-4 text-cyan-400 shrink-0" />
              <span>+998 (71) 123-45-67</span>
            </div>
            <div className="flex items-center gap-2.5">
              <Mail className="w-4 h-4 text-cyan-400 shrink-0" />
              <span>info@qurilishloyiha.uz</span>
            </div>
          </div>
        </div>
      </div>

      <div className="container border-t border-slate-900 mt-12 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
        <p>© {new Date().getFullYear()} QurilishLoyiha.uz — Barcha huquqlar himoyalangan.</p>
        <div className="flex items-center gap-1">
          <span>Yaratildi: Next.js + React bilan</span>
        </div>
      </div>
    </footer>
  );
}
