const pool = mysql.createPool({
  host: 'localhost',
  user: 'askdlbbc_test_user',
  password: 'O=qkm@eF]^#E', // Change if you set a password in XAMPP
  database: 'askdlbbc_ailifecoach',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
});