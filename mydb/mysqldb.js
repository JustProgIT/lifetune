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

export async function initDb() {
  const conn = await pool.getConnection();
  try {
    // Create users table first (needed for FK)
    await conn.query(`
      CREATE TABLE IF NOT EXISTS users (
        id VARCHAR(255) PRIMARY KEY,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        )
    `);

    // Create preferences table
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

    // Create analytics table
    await conn.query(`
    CREATE TABLE IF NOT EXISTS analytics (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id VARCHAR(255),
        event_type VARCHAR(255),
        event_data JSON,
        timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
    `);

    console.log("✅ MySQL Database initialized");
  } finally {
    conn.release();
  }
  return pool;
}

// Save or update user preferences
export async function savePreferences(userId, preferences) {
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

  await pool.query(
    `
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
    `,
    [
        userId,
        ageGroup || null,
        occupation || null,
        livingSituation || null,
        relationshipStatus || null,
        personalityType || null,
        coachingStyle || null,
        stressRelievers || null,
        problemSolvingMethod || null
    ]
    );
}

// Get preferences as an object
export async function getPreferences(userId) {
  const [rows] = await pool.query(
    `SELECT ageGroup, occupation, livingSituation, relationshipStatus, personalityType, coachingStyle, stressRelievers, problemSolvingMethod 
     FROM preferences 
     WHERE user_id = ?`,
    [userId]
  );

  if (rows.length === 0) {
    return null;
  }

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



// Log analytics event
export async function logUserInteraction(userId, eventType, data) {
  await pool.query(
    `INSERT INTO analytics (user_id, event_type, event_data)
    VALUES (?, ?, ?)`,
    [userId, eventType, JSON.stringify(data)]
    );
}

// Check if daily API call limit exceeded
export async function hasExceededDailyLimit(userId, limit) {
  const [rows] = await pool.query(
    `SELECT COUNT(*) AS count FROM analytics
     WHERE user_id = ?
       AND event_type = 'api_cost'
       AND timestamp >= NOW() - INTERVAL 1 DAY`,
    [userId]
  );
  return rows[0].count >= limit;
}

// Get remaining daily API budget
export async function getRemainingDailyBudget(userId, limit) {
  const [rows] = await pool.query(
    `SELECT COUNT(*) AS count FROM analytics
     WHERE user_id = ?
       AND event_type = 'api_cost'
       AND timestamp >= NOW() - INTERVAL 1 DAY`,
    [userId]
  );
  return limit - rows[0].count;
}

// Retrieve the latest limit_exceeded timestamp for a user
export async function getLatestLimitExceeded(userId) {
  try {
    // Assuming you are using MySQL pool
    const [rows] = await pool.query(
      `SELECT timestamp 
       FROM analytics 
       WHERE user_id = ? AND event_type = 'limit_exceeded' 
       ORDER BY timestamp DESC 
       LIMIT 1`,
      [userId]
    );

    if (rows.length === 0) {
      return null; // no record found
    }

    return rows[0].timestamp; // return the timestamp
  } catch (err) {
    console.error('Error fetching limit_exceeded timestamp:', err);
    throw err;
  }
}

// Open DB connection
export async function openDb() {
  return pool;
}
