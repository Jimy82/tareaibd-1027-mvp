CREATE TABLE IF NOT EXISTS submissions (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(80) NOT NULL,
  email VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  ip VARBINARY(16) NULL,
  user_agent VARCHAR(255) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_submissions_email_created ON submissions (email, created_at);
