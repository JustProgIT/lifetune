/**
 * AI Coach Server
 * Refactored for coding conventions and readability
 */

import express from 'express';
import cors from 'cors';
import helmet from 'helmet';
import rateLimit from 'express-rate-limit';
import session from 'express-session';
import FileStore from 'session-file-store';
import connectSqlite3 from 'connect-sqlite3';
import { v4 as uuidv4 } from 'uuid';
import { body, validationResult } from 'express-validator';
import winston from 'winston';
import dotenv from 'dotenv';
import { GoogleGenAI } from '@google/genai';

import {
  sql_initDb,
  sql_savePreferences,
  sql_getPreferences,
  sql_logUserInteraction,
  sql_hasExceededDailyLimit,
  sql_getRemainingDailyBudget,
  sql_getLatestLimitExceeded,
  sql_openDb
} from '../mydb/mysqldb.js';

// === CONFIGURATION & ENVIRONMENT ===
dotenv.config();
//process.env.PORT ||
const PORT = 3000;
const NODE_ENV = process.env.NODE_ENV || 'development';
const GOOGLE_API_KEY = process.env.GOOGLE_API_KEY;
if (!GOOGLE_API_KEY) {
  console.error('GOOGLE_API_KEY environment variable is not set!');
  process.exit(1);
}

// === LOGGER SETUP ===
const logger = winston.createLogger({
  level: 'debug',
  format: winston.format.combine(
    winston.format.timestamp(),
    winston.format.json()
  ),
  defaultMeta: { service: 'ai-coach' },
  transports: [
    new winston.transports.File({ filename: 'error.log', level: 'error' }),
    new winston.transports.File({ filename: 'combined.log' }),
    new winston.transports.Console({ 
      format: winston.format.combine(
        winston.format.colorize(),
        winston.format.simple()
      )
    })
  ],
});

// === DATABASE INITIALIZATION ===
let db;
(async () => {
  try {
    db = await sql_initDb();
    logger.info('Database initialized successfully');
  } catch (err) {
    logger.error('Database initialization error', { error: err.message });
  }
})();

// === EXPRESS APP ===
const app = express();

// === SESSION STORE & MANAGEMENT ===
const FileStoreSession = FileStore(session);
const SQLiteStore = connectSqlite3(session);

const sessionStore = NODE_ENV === 'production'
  ? new SQLiteStore({ db: 'sessions.db', dir: './sessions', concurrentDB: true })
  : new FileStoreSession({ path: './sessions', ttl: 86400, retries: 0, logFn: (...args) => logger.debug(...args) });

app.use(session({
  store: sessionStore,
  secret: process.env.SESSION_SECRET || 'ai-coach-secret',
  name: 'ai_coach_sid',
  resave: false,
  saveUninitialized: false,
  rolling: true,
  cookie: { 
    maxAge: 30 * 24 * 60 * 60 * 1000,
    httpOnly: true,
    secure: NODE_ENV === 'production',
    sameSite: 'lax'
  }
}));

sessionStore.on('error', error => logger.error('Session store error', { error: error.message }));

// === SECURITY MIDDLEWARE ===
app.use(helmet({
  contentSecurityPolicy: {
    directives: {
      defaultSrc: ["'self'"],
      scriptSrc: ["'self'", "'unsafe-inline'"],
      styleSrc: ["'self'", "'unsafe-inline'"],
      imgSrc: ["'self'", "data:"],
      connectSrc: ["'self'"],
      fontSrc: ["'self'"],
      objectSrc: ["'none'"],
      mediaSrc: ["'self'"],
      frameSrc: ["'none'"]
    }
  }
}));

app.use(rateLimit({
  windowMs: 15 * 60 * 1000,
  max: 100,
  standardHeaders: true,
  legacyHeaders: false,
  message: 'Too many requests from this IP, please try again later.',
  handler: (req, res, next, options) => {
    logger.warn(`Rate limit exceeded: ${req.ip}`);
    res.status(options.statusCode).json({ error: options.message });
  }
}));

// JSON parser and CORS
app.use(express.json({ limit: '100kb' }));
app.use(cors({ origin: "http://localhost:8000", credentials: true }));

// Static files
app.use(express.static('.', {
  setHeaders: (res, path) => {
    if (path.endsWith('.html')) res.setHeader('Cache-Control', 'no-cache');
    else if (path.endsWith('.css') || path.endsWith('.js')) res.setHeader('Cache-Control', 'no-cache');
  }
}));

// === AI MODEL SETUP ===
const ai = new GoogleGenAI({ apiKey: GOOGLE_API_KEY });
const language = 'Mandarin';

// === HELPER FUNCTIONS ===
function sendApiError(err, req, res) {
  logger.error('API Error', { error: err.message, stack: err.stack });
  let status = 500;
  let message = 'Sorry, something went wrong. Please try again.';

  if (err.name === 'ValidationError') status = 400, message = 'Please check your input.';
  else if (err.code === 'ECONNREFUSED') status = 503, message = 'Service unavailable.';
  else if (err.message.includes('rate limit')) status = 429, message = 'Too many requests.';

  res.status(status).json({ error: message, requestId: uuidv4().slice(0, 8) });
}

function formatUserPreferences(preferences) {
  if (!preferences || Object.keys(preferences).length === 0) return '';
  return `
**User Preferences:**
- Age Group: ${preferences.ageGroup || 'Not specified'}
- Occupation: ${preferences.occupation || 'Not specified'}
- Living Situation: ${preferences.livingSituation || 'Not specified'}
- Relationship Status: ${preferences.relationshipStatus || 'Not specified'}
- Personality Type: ${preferences.personalityType || 'Not specified'}
- Preferred Coaching Style: ${preferences.coachingStyle || 'Not specified'}
- Stress Relievers: ${preferences.stressRelievers || 'Not specified'}
- Problem-Solving Approach: ${preferences.problemSolvingMethod || 'Not specified'}
`;
}

async function handleLimitExceeded(userId, limit = 0.10) {
  try {
    await sql_logUserInteraction(userId, 'limit_exceeded', { dailyLimit: limit });
    const timestamp = await sql_getLatestLimitExceeded(userId);
    const resetTime = new Date(timestamp ? new Date(timestamp + 'Z') : Date.now());
    resetTime.setHours(resetTime.getHours() + 24);
    return { resetTime };
  } catch (error) {
    const resetTime = new Date();
    resetTime.setDate(resetTime.getDate() + 1);
    return { resetTime, error: true };
  }
}

async function generateResponse(historys, systemInstructions) {
  return ai.models.generateContent({
    model: 'gemini-2.5-pro',
    config: {
      thinkingConfig: { thinkingBudget: -1, includeThoughts: true },
      httpOptions: { timeout: 30 * 1000 },
      tools: [{}],
      systemInstruction: systemInstructions,
      maxOutputTokens: 3000
    },
    contents: historys
  });
}

// === ROUTES ===

// Check user budget
app.get('/user-budget', async (req, res) => {
  try {
    if (!req.session?.userId) return res.json({ remaining: 0.5, isNewUser: true });

    const DAILY_LIMIT = 0.10;
    const remaining = await sql_getRemainingDailyBudget(req.session.userId, DAILY_LIMIT);
    const hasExceeded = await sql_hasExceededDailyLimit(req.session.userId, DAILY_LIMIT);
    const resetTime = hasExceeded ? await handleLimitExceeded(req.session.userId, DAILY_LIMIT) : null;

    res.json({ remaining, isNewUser: false, limitExceeded: hasExceeded, resetTime });
  } catch (err) { sendApiError(err, req, res); }
});

// Chat endpoint
app.post('/chat', [
  body('historys').isArray(),
  body('userMessage').isString(),
  body('userPreferences').optional().isObject()
], async (req, res) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) return res.status(400).json({ error: 'Invalid input', details: errors.array() });

    let { historys, userMessage, userPreferences } = req.body;
    if (!req.session.userId) req.session.userId = uuidv4();

    const DAILY_LIMIT = 0.10;
    if (await sql_hasExceededDailyLimit(req.session.userId, DAILY_LIMIT)) {
      const { resetTime } = await handleLimitExceeded(req.session.userId, DAILY_LIMIT);
      return res.status(429).json({ error: `Daily limit reached`, limitExceeded: true, resetTime });
    }

    const userPrefString = formatUserPreferences(userPreferences);
    const systemInstructions = `**Core Role:** You are a helpful AI coach.\n${userPrefString}`;
    historys.push({ role: 'user', parts: [{ text: userMessage }] });

    const response = await generateResponse(historys, systemInstructions);
    const parts = response.candidates[0]?.content?.parts;
    if (!parts?.length) return res.status(500).json({ error: 'AI response empty' });

    const modelText = parts[0].text;
    historys.push({ role: 'model', parts: [{ text: modelText }] });

    res.json({ modelMessage: modelText, historys });
  } catch (err) { sendApiError(err, req, res); }
});

// 404 handler
app.use((req, res) => res.status(404).json({ error: 'Not Found' }));
app.use((err, req, res, next) => sendApiError(err, req, res));

// === SERVER STARTUP ===
const gracefulShutdown = () => {
  logger.info('Shutting down server gracefully');
  if (sessionStore.close) sessionStore.close();
  if (db?.close) db.close();
  process.exit(0);
};

process.on('SIGTERM', gracefulShutdown);
process.on('SIGINT', gracefulShutdown);

app.listen(PORT, () => logger.info(`Server running on port ${PORT}`));
