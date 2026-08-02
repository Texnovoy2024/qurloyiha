import { NextResponse } from 'next/server';
import { query } from '@/lib/db';
import { initialProblems } from '@/lib/mockData';

export async function GET(request) {
  const { searchParams } = new URL(request.url);
  const categoryId = searchParams.get('category');
  const search = searchParams.get('search');
  const status = searchParams.get('status');

  try {
    let sql = `
      SELECT p.*, c.name_uz as category_name, u.username as company_name 
      FROM problem p 
      LEFT JOIN category c ON p.category_id = c.id 
      LEFT JOIN user u ON p.company_id = u.id 
      WHERE 1=1
    `;
    const params = [];

    if (categoryId) {
      sql += ` AND p.category_id = ?`;
      params.push(categoryId);
    }
    if (status) {
      sql += ` AND p.status = ?`;
      params.push(status);
    }
    if (search) {
      sql += ` AND (p.title LIKE ? OR p.description LIKE ?)`;
      params.push(`%${search}%`, `%${search}%`);
    }

    sql += ` ORDER BY p.created_at DESC`;

    const rows = await query(sql, params);
    if (rows && rows.length > 0) {
      return NextResponse.json({ success: true, data: rows });
    }
  } catch (e) {
    console.warn('MySQL fallback used for problems GET:', e.message);
  }

  // Filter fallback mock data
  let filtered = [...initialProblems];
  if (categoryId) {
    filtered = filtered.filter(p => p.category_id === Number(categoryId));
  }
  if (status) {
    filtered = filtered.filter(p => p.status === Number(status));
  }
  if (search) {
    const q = search.toLowerCase();
    filtered = filtered.filter(p => p.title.toLowerCase().includes(q) || p.description.toLowerCase().includes(q));
  }

  return NextResponse.json({ success: true, data: filtered });
}

export async function POST(request) {
  try {
    const body = await request.json();
    const { title, description, requirements, expected_result, budget, category_id, company_id } = body;

    if (!title || !description) {
      return NextResponse.json({ success: false, message: 'Sarlavha va Tavsif kiritilishi shart' }, { status: 400 });
    }

    const now = Math.floor(Date.now() / 1000);
    try {
      const res = await query(
        `INSERT INTO problem (company_id, category_id, title, description, requirements, expected_result, budget, status, created_at, updated_at) 
         VALUES (?, ?, ?, ?, ?, ?, ?, 10, ?, ?)`,
        [company_id || 2, category_id || 1, title, description, requirements || '', expected_result || '', budget || 0, now, now]
      );
      return NextResponse.json({ success: true, id: res.insertId, message: 'Masala muvaffaqiyatli eʼlon qilindi!' });
    } catch (dbErr) {
      console.warn('DB insert fallback:', dbErr.message);
      const newProblem = {
        id: Date.now(),
        company_id: company_id || 2,
        company_name: 'Apex Construction LLC',
        category_id: Number(category_id) || 1,
        category_name: 'Muhandislik va Konstruksiyalar',
        title,
        description,
        requirements,
        expected_result,
        budget: Number(budget) || 5000,
        status: 10,
        views_count: 1,
        created_at: now
      };
      initialProblems.unshift(newProblem);
      return NextResponse.json({ success: true, id: newProblem.id, message: 'Masala muvaffaqiyatli saqlandi!' });
    }
  } catch (err) {
    return NextResponse.json({ success: false, message: err.message }, { status: 500 });
  }
}
