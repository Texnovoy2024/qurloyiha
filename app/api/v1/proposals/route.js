import { NextResponse } from 'next/server';
import { query } from '@/lib/db';
import { initialProposals } from '@/lib/mockData';

export async function POST(request) {
  try {
    const body = await request.json();
    const { problem_id, scientist_id, title, description, solution_details, budget_offer, time_offer } = body;

    if (!problem_id || !title || !description || !solution_details) {
      return NextResponse.json({ success: false, message: 'Barcha talab qilingan maydonlarni toʻldiring' }, { status: 400 });
    }

    const now = Math.floor(Date.now() / 1000);
    try {
      const res = await query(
        `INSERT INTO proposal (problem_id, scientist_id, title, description, solution_details, budget_offer, time_offer, status, created_at, updated_at) 
         VALUES (?, ?, ?, ?, ?, ?, ?, 10, ?, ?)`,
        [problem_id, scientist_id || 3, title, description, solution_details, budget_offer || 0, time_offer || '', now, now]
      );
      return NextResponse.json({ success: true, id: res.insertId, message: 'Taklifingiz muvaffaqiyatli yuborildi!' });
    } catch (e) {
      console.warn('DB proposal insert fallback:', e.message);
      const newProp = {
        id: Date.now(),
        problem_id: Number(problem_id),
        scientist_id: scientist_id || 3,
        scientist_name: 'Prof. Alisher Usmanov',
        title,
        description,
        solution_details,
        budget_offer: Number(budget_offer) || 10000,
        time_offer: time_offer || '30 kun',
        status: 10,
        created_at: now
      };
      initialProposals.unshift(newProp);
      return NextResponse.json({ success: true, id: newProp.id, message: 'Taklifingiz muvaffaqiyatli qabul qilindi!' });
    }
  } catch (err) {
    return NextResponse.json({ success: false, message: err.message }, { status: 500 });
  }
}
