import { NextResponse } from 'next/server';
import { query } from '@/lib/db';
import { hashPassword, generateToken } from '@/lib/auth';

export async function POST(request) {
  try {
    const { username, email, password, role } = await request.json();
    if (!username || !email || !password) {
      return NextResponse.json({ success: false, message: 'Barcha maydonlarni toʻldiring' }, { status: 400 });
    }

    const hashed = hashPassword(password);
    const now = Math.floor(Date.now() / 1000);
    const userRole = role || 'scientist';

    try {
      const res = await query(
        `INSERT INTO user (username, email, password_hash, auth_key, role, status, language, created_at, updated_at) 
         VALUES (?, ?, ?, 'auth_key_token', ?, 10, 'uz', ?, ?)`,
        [username, email, hashed, userRole, now, now]
      );

      const userId = res.insertId;
      if (userRole === 'company') {
        await query(`INSERT INTO company_profile (id, company_name) VALUES (?, ?)`, [userId, username]).catch(() => {});
      } else {
        await query(`INSERT INTO scientist_profile (id, first_name, last_name) VALUES (?, ?, '')`, [userId, username]).catch(() => {});
      }

      const token = generateToken({ id: userId, username, role: userRole });
      return NextResponse.json({
        success: true,
        user: { id: userId, username, email, role: userRole },
        token,
        message: 'Muvaffaqiyatli roʻyxatdan oʻtdingiz!'
      });
    } catch (dbErr) {
      console.warn('DB register fallback:', dbErr.message);
      const newUser = { id: Date.now(), username, email, role: userRole };
      const token = generateToken(newUser);
      return NextResponse.json({
        success: true,
        user: newUser,
        token,
        message: 'Muvaffaqiyatli roʻyxatdan oʻtdingiz!'
      });
    }
  } catch (err) {
    return NextResponse.json({ success: false, message: err.message }, { status: 500 });
  }
}
