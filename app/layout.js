import './globals.css';
import Navbar from '@/components/Navbar';
import Footer from '@/components/Footer';

export const metadata = {
  title: 'QurilishLoyiha.uz — Qurilish va Ilmiy Innovatsiyalar Ekotizimi',
  description: 'Oʻzbekiston qurilish korxonalari va ilmiy-tadqiqot institutlarini birlashtiruvchi, innovatsion muhandislik yechimlarini tahlil qiluvchi yagona platforma.',
  keywords: ['qurilish', 'innovatsiya', 'muhandislik', 'beton', 'seysmik', 'ilm-fan', 'tadqiqot', 'Oʻzbekiston'],
  authors: [{ name: 'QurilishLoyiha Team' }],
};

export default function RootLayout({ children }) {
  return (
    <html lang="uz" className="dark">
      <body className="bg-slate-950 text-slate-100 min-h-screen flex flex-col antialiased">
        <Navbar />
        <main className="flex-grow">
          {children}
        </main>
        <Footer />
      </body>
    </html>
  );
}
