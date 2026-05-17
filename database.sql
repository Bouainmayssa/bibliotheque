CREATE DATABASE IF NOT EXISTS bibliotheque CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bibliotheque;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user'
);

CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    type VARCHAR(100),
    image VARCHAR(255) NOT NULL,
    description TEXT
);

CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    status ENUM('en_attente','acceptee','refusee') NOT NULL DEFAULT 'en_attente',
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_res_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_res_book FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);

INSERT INTO users (name, email, password, role)
VALUES (
    'Admin',
    'admin@mail.com',
    '$2y$10$U3dHqi.b0v1cP8veW01aEuBu2MjO13hDEMBXWd75H3hM1Qdl88g82',
    'admin'
)
ON DUPLICATE KEY UPDATE role = 'admin';
