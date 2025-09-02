// db.js - MySQL version for XAMPP with working timestamps
import mysql from 'mysql2/promise';

const pool = mysql.createPool({
    host: 'localhost',
    user: 'root',
    password: '', // Change if you set a password in XAMPP
    database: 'ailifecoach',
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0
});

// ----------------------------
// Database Initialization
// ----------------------------
export async function sql_initDb() {
    const conn = await pool.getConnection();
    try {
        // Users table (needed for FK)
        await conn.query(`
            CREATE TABLE IF NOT EXISTS users (
                id VARCHAR(255) PRIMARY KEY,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        `);

        // Preferences table
        await conn.query(`
            CREATE TABLE IF NOT EXISTS preferences (
                user_id TEXT PRIMARY KEY,
                ageGroup TEXT,
                occupation TEXT,
                livingSituation TEXT,
                relationshipStatus TEXT,
                personalityType TEXT,
                coachingStyle TEXT,
                stressRelievers TEXT,
                problemSolvingMethod TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users (id)
            )
        `);

        // Analytics table
        await conn.query(`
            CREATE TABLE IF NOT EXISTS analytics (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id VARCHAR(255),
                event_type VARCHAR(255),
                event_data JSON,
                timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        `);

        await conn.query(`
          CREATE TABLE IF NOT EXISTS chat_histories (
            user_id VARCHAR(255) NOT NULL,
            chatbot_type VARCHAR(255) NOT NULL,
            history JSON,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (user_id, chatbot_type),
            FOREIGN KEY (user_id) REFERENCES users (id)
          )
        `);

        console.log("✅ MySQL Database initialized");
    } finally {
        conn.release();
    }

    return pool;
}

// ----------------------------
// Preferences
// ----------------------------

// Save or update user preferences
export async function sql_savePreferences(userId, preferences) {
    const {
        ageGroup,
        occupation,
        livingSituation,
        relationshipStatus,
        personalityType,
        coachingStyle,
        stressRelievers,
        problemSolvingMethod
    } = preferences;

    await pool.query(`
        INSERT INTO preferences (
            user_id, ageGroup, occupation, livingSituation,
            relationshipStatus, personalityType, coachingStyle,
            stressRelievers, problemSolvingMethod, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ON DUPLICATE KEY UPDATE
            ageGroup = VALUES(ageGroup),
            occupation = VALUES(occupation),
            livingSituation = VALUES(livingSituation),
            relationshipStatus = VALUES(relationshipStatus),
            personalityType = VALUES(personalityType),
            coachingStyle = VALUES(coachingStyle),
            stressRelievers = VALUES(stressRelievers),
            problemSolvingMethod = VALUES(problemSolvingMethod),
            updated_at = NOW()
    `, [
        userId,
        ageGroup || null,
        occupation || null,
        livingSituation || null,
        relationshipStatus || null,
        personalityType || null,
        coachingStyle || null,
        stressRelievers || null,
        problemSolvingMethod || null
    ]);
}

// Get preferences
export async function sql_getPreferences(userId) {
    const [rows] = await pool.query(`
        SELECT ageGroup, occupation, livingSituation, relationshipStatus,
               personalityType, coachingStyle, stressRelievers, problemSolvingMethod
        FROM preferences
        WHERE user_id = ?
    `, [userId]);

    if (rows.length === 0) return null;

    return {
        ageGroup: rows[0].ageGroup,
        occupation: rows[0].occupation,
        livingSituation: rows[0].livingSituation,
        relationshipStatus: rows[0].relationshipStatus,
        personalityType: rows[0].personalityType,
        coachingStyle: rows[0].coachingStyle,
        stressRelievers: rows[0].stressRelievers,
        problemSolvingMethod: rows[0].problemSolvingMethod
    };
}

// Get chat history for a specific chatbot type (MySQL)
export async function getChatHistory(userId, chatbotType) {
  const [rows] = await conn.query(
    'SELECT history FROM chat_histories WHERE user_id = ? AND chatbot_type = ? LIMIT 1',
    [userId, chatbotType]
  );

  if (!rows.length) return [];

  const h = rows[0].history;
  // mysql2 may return JSON as an object (with typeCast) or as a string.
  try {
    return typeof h === 'string' ? JSON.parse(h) : h;
  } catch {
    return [];
  }
}

// Save chat history for a specific chatbot type (MySQL)
export async function saveChatHistory(userId, chatbotType, history) {
  const payload = JSON.stringify(history);

  await conn.query(
    `INSERT INTO chat_histories (user_id, chatbot_type, history)
     VALUES (?, ?, CAST(? AS JSON))
     ON DUPLICATE KEY UPDATE
       history = VALUES(history),
       updated_at = CURRENT_TIMESTAMP`,
    [userId, chatbotType, payload]
  );
}

// ----------------------------
// Analytics
// ----------------------------

// Log analytics event
export async function sql_logUserInteraction(userId, eventType, data) {
    await pool.query(`
        INSERT INTO analytics (user_id, event_type, event_data)
        VALUES (?, ?, ?)
    `, [userId, eventType, JSON.stringify(data)]);
}

// Check if daily API call limit exceeded
export async function sql_hasExceededDailyLimit(userId, limit) {
    const [rows] = await pool.query(`
        SELECT COUNT(*) AS count FROM analytics
        WHERE user_id = ?
          AND event_type = 'api_cost'
          AND timestamp >= NOW() - INTERVAL 1 DAY
    `, [userId]);

    return rows[0].count >= limit;
}

// Get remaining daily API budget
export async function sql_getRemainingDailyBudget(userId, limit) {
    const [rows] = await pool.query(`
        SELECT COUNT(*) AS count FROM analytics
        WHERE user_id = ?
          AND event_type = 'api_cost'
          AND timestamp >= NOW() - INTERVAL 1 DAY
    `, [userId]);

    return limit - rows[0].count;
}

// Get latest limit_exceeded timestamp
export async function sql_getLatestLimitExceeded(userId) {
    try {
        const [rows] = await pool.query(`
            SELECT timestamp
            FROM analytics
            WHERE user_id = ? AND event_type = 'limit_exceeded'
            ORDER BY timestamp DESC
            LIMIT 1
        `, [userId]);

        if (rows.length === 0) return null;

        return rows[0].timestamp;
    } catch (err) {
        console.error('Error fetching limit_exceeded timestamp:', err);
        throw err;
    }
}

// ----------------------------
// DB Connection
// ----------------------------
export async function sql_openDb() {
    return pool;
}
