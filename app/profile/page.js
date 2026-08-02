'use client';

import { useState, useEffect } from 'react';
import { User, Building2, GraduationCap, ShieldCheck, Mail, Phone, MapPin, Save } from 'lucide-react';

export default function ProfilePage() {
  const [user, setUser] = useState({
    username: 'Demo User',
    email: 'user@qurloyiha.uz',
    role: 'scientist',
    phone: '+998 90 123-45-67',
    institution: 'Toshkent Davlat Texnika Universiteti',
    specialization: 'Qurilish materiallari va seysmik xavfsizlik',
    bio: 'Muhandis-konstruktor, 10 yillik tajribaga ega ilmiy xodim.'
  });
  const [saved, setSaved] = useState(false);

  useEffect(() => {
    const savedUser = localStorage.getItem('user');
    if (savedUser) {
      try {
        const u = JSON.parse(savedUser);
        setUser(prev => ({ ...prev, ...u }));
      } catch (e) {}
    }
  }, []);

  const handleSave = (e) => {
    e.preventDefault();
    localStorage.setItem('user', JSON.stringify(user));
    setSaved(true);
    setTimeout(() => setSaved(false), 2000);
  };

  return (
    <div className="max-w-3xl mx-auto px-4 py-10 space-y-8">
      <div className="clean-card p-6 sm:p-10 space-y-8">
        <div className="flex items-center gap-4 border-b border-slate-100 pb-6">
          <div className="w-16 h-16 rounded-2xl bg-sky-600 text-white flex items-center justify-center font-bold text-3xl shadow-md">
            {user.username[0]?.toUpperCase() || 'U'}
          </div>
          <div>
            <span className="text-xs font-bold px-2.5 py-0.5 rounded-full bg-sky-100 text-sky-800 uppercase border border-sky-200">
              {user.role}
            </span>
            <h1 className="text-2xl font-bold text-slate-900 font-display mt-1">{user.username}</h1>
            <p className="text-xs text-slate-500">{user.email}</p>
          </div>
        </div>

        {saved && (
          <div className="p-3.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold text-center">
            Profil ma'lumotlari muvaffaqiyatli saqlandi!
          </div>
        )}

        <form onSubmit={handleSave} className="space-y-5">
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div className="form-group">
              <label>Foydalanuvchi Nomi</label>
              <input
                type="text"
                value={user.username}
                onChange={(e) => setUser({ ...user, username: e.target.value })}
                className="form-input"
              />
            </div>
            <div className="form-group">
              <label>Email</label>
              <input
                type="email"
                value={user.email}
                onChange={(e) => setUser({ ...user, email: e.target.value })}
                className="form-input"
              />
            </div>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div className="form-group">
              <label>Telefon Raqam</label>
              <input
                type="text"
                value={user.phone}
                onChange={(e) => setUser({ ...user, phone: e.target.value })}
                className="form-input"
              />
            </div>
            <div className="form-group">
              <label>Tashkilot / Institut</label>
              <input
                type="text"
                value={user.institution}
                onChange={(e) => setUser({ ...user, institution: e.target.value })}
                className="form-input"
              />
            </div>
          </div>

          <div className="form-group">
            <label>Ixtisoslik va Mutaxassislik Yo'nalishi</label>
            <input
              type="text"
              value={user.specialization}
              onChange={(e) => setUser({ ...user, specialization: e.target.value })}
              className="form-input"
            />
          </div>

          <div className="form-group">
            <label>Olim / Korxona Haqida Qisqacha (Bio)</label>
            <textarea
              rows={4}
              value={user.bio}
              onChange={(e) => setUser({ ...user, bio: e.target.value })}
              className="form-input"
            />
          </div>

          <div className="pt-4 flex justify-end border-t border-slate-100">
            <button type="submit" className="btn-primary py-3 px-8 text-xs font-bold shadow-md">
              <Save className="w-4 h-4" />
              <span>Ma'lumotlarni Saqlash</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
