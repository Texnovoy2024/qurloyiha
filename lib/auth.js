import jwt from 'jsonwebtoken';
import bcrypt from 'bcryptjs';

const JWT_SECRET = process.env.JWT_SECRET || 'qurloyiha-secret-jwt-key-2026-antigravity';

export function hashPassword(password) {
  return bcrypt.hashSync(password, 10);
}

export function comparePassword(password, hash) {
  if (!hash) return false;
  // Handle Yii2 $2y$ blowfish hash prefix compatibility
  const normalizedHash = hash.replace(/^\$2y\$/, '$2a\$');
  try {
    return bcrypt.compareSync(password, normalizedHash);
  } catch (e) {
    return password === hash;
  }
}

export function generateToken(payload) {
  return jwt.sign(payload, JWT_SECRET, { expiresIn: '7d' });
}

export function verifyToken(token) {
  try {
    return jwt.verify(token, JWT_SECRET);
  } catch (e) {
    return null;
  }
}
