-- Simple ecommerce schema
CREATE DATABASE IF NOT EXISTS ecommerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecommerce;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  is_admin TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(120) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT,
  title VARCHAR(200) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  image VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  total DECIMAL(10,2) NOT NULL,
  status VARCHAR(50) DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL DEFAULT 1,
  price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- Seed admin user (password: admin123)
INSERT IGNORE INTO users (name, email, password, is_admin)
VALUES ('Admin', 'admin@shop.test', '$2y$10$wH2oQjZz0m0a6h0lY5boge9w/0bU6x6f6XwFZo7R9Qf6Q0xqGf7yK', 1);

-- sample categories
INSERT IGNORE INTO categories (name, slug) VALUES ('Clothing','clothing'), ('Accessories','accessories'), ('Electronics','electronics');

-- sample products
INSERT IGNORE INTO products (category_id, title, description, price, image)
VALUES
(1, 'Minimal T-Shirt', 'Comfortable cotton tee.', 19.99, NULL),
(2, 'Leather Wallet', 'Slim leather wallet.', 39.50, NULL),
(3, 'Wireless Earbuds', 'Compact earbuds with good sound.', 79.00, NULL);
