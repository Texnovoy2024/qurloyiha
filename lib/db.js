import mysql from 'mysql2/promise';

// Supports both local and cloud MySQL (Railway, Vercel, PlanetScale, etc.)
const dbConfig = {
  host: process.env.MYSQLHOST || process.env.DB_HOST || 'localhost',
  port: parseInt(process.env.MYSQLPORT || process.env.DB_PORT || '3306'),
  user: process.env.MYSQLUSER || process.env.DB_USER || 'root',
  password: process.env.MYSQLPASSWORD || process.env.DB_PASSWORD || '',
  database: process.env.MYSQLDATABASE || process.env.DB_NAME || 'yii2basic',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0,
};

let pool = null;

try {
  pool = mysql.createPool(dbConfig);
} catch (e) {
  console.warn('MySQL pool initialization error:', e.message);
}

export async function query(sql, params = []) {
  if (!pool) {
    throw new Error('Database pool not initialized');
  }
  try {
    const [rows] = await pool.execute(sql, params);
    return rows;
  } catch (error) {
    console.error('Database query error:', error.message);
    throw error;
  }
}

export default pool;
