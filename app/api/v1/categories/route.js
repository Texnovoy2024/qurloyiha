import { NextResponse } from 'next/server';
import { query } from '@/lib/db';
import { initialCategories } from '@/lib/mockData';

export async function GET() {
  try {
    const rows = await query('SELECT * FROM category ORDER BY id ASC');
    if (rows && rows.length > 0) {
      return NextResponse.json({ success: true, data: rows });
    }
  } catch (e) {
    console.warn('MySQL fallback used for categories:', e.message);
  }
  return NextResponse.json({ success: true, data: initialCategories });
}
