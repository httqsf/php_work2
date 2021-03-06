DROP TABLE IF EXISTS textmemos;

CREATE TABLE textmemos(
    id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    text VARCHAR(1000),
    date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) DEFAULT CHARACTER SET = utf8mb4;
