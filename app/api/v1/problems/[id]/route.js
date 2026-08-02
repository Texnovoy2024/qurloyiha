import { NextResponse } from 'next/server';
import { query } from '@/lib/db';
import { initialProblems, initialProposals } from '@/lib/mockData';

export async function GET(request, { params }) {
  const problemId = params.id;

  try {
    const rows = await query(
      `SELECT p.*, c.name_uz as category_name, u.username as company_name 
       FROM problem p 
       LEFT JOIN category c ON p.category_id = c.id 
       LEFT JOIN user u ON p.company_id = u.id 
       WHERE p.id = ?`,
      [problemId]
    );

    if (rows && rows.length > 0) {
      // Increment view count asynchronously
      query(`UPDATE problem SET views_count = views_count + 1 WHERE id = ?`, [problemId]).catch(() => {});
      
      // Fetch related proposals for this challenge
      const proposals = await query(
        `SELECT pr.*, u.username as scientist_name 
         FROM proposal pr 
         LEFT JOIN user u ON pr.scientist_id = u.id 
         WHERE pr.problem_id = ? ORDER BY pr.created_at DESC`,
        [problemId]
      ).catch(() => []);

      return NextResponse.json({ success: true, data: { ...rows[0], proposals } });
    }
  } catch (e) {
    console.warn('MySQL fallback for problem detail:', e.message);
  }

  const problem = initialProblems.find(p => p.id === Number(problemId)) || initialProblems[0];
  const proposals = initialProposals.filter(pr => pr.problem_id === Number(problemId));

  return NextResponse.json({
    success: true,
    data: { ...problem, proposals }
  });
}
