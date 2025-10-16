-- Membuat tabel users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role TINYINT DEFAULT 0 -- 0: member, 1: admin
);

-- Membuat tabel articles
CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    author INT,
    FOREIGN KEY (author) REFERENCES users (id)
);

-- Insert data ke tabel users
INSERT INTO
    users (name, email, password, role)
VALUES (
        'admin',
        'admin@example.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        1
    ),
    -- hash bcrypt dari 'password'
    (
        'member1',
        'member1@example.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        0
    ),
    -- hash bcrypt dari 'password'
    (
        'member2',
        'member2@example.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        0
    );
-- hash bcrypt dari 'password'

-- Insert data ke tabel articles
INSERT INTO
    articles (title, content, author)
VALUES (
        'Artikel Pertama',
        'Ini adalah artikel pertama.',
        1
    ), -- Author = admin
    (
        'Artikel Kedua',
        'Ini adalah artikel kedua.',
        2
    ), -- Author = member1
    (
        'Artikel Ketiga',
        'Ini adalah artikel ketiga.',
        3
    );
-- Author = member2