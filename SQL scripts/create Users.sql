CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    remember_token TEXT,
    email_verified_at TEXT, -- SQLite does not have a TIMESTAMP type, TEXT is used and you should store the dates in ISO8601 strings ("YYYY-MM-DD HH:MM:SS.SSS").
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT DEFAULT CURRENT_TIMESTAMP
);