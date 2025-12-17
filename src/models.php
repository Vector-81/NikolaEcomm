<?php
require_once __DIR__ . '/db.php';

function getCategories() {
    global $pdo;
    $stmt = $pdo->query('SELECT * FROM categories ORDER BY name');
    return $stmt->fetchAll();
}

function getProducts($limit = 100) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id=c.id ORDER BY p.created_at DESC LIMIT ?');
    $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getProduct($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addProduct($data) {
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO products (category_id, title, description, price, image) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$data['category_id'] ?: null, $data['title'], $data['description'], $data['price'], $data['image']]);
    return $pdo->lastInsertId();
}

function updateProduct($id, $data) {
    global $pdo;
    $stmt = $pdo->prepare('UPDATE products SET category_id=?, title=?, description=?, price=?, image=? WHERE id = ?');
    return $stmt->execute([$data['category_id'] ?: null, $data['title'], $data['description'], $data['price'], $data['image'], $id]);
}

function deleteProduct($id) {
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
    return $stmt->execute([$id]);
}

function createUser($name, $email, $password, $is_admin = 0) {
    global $pdo;
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (name,email,password,is_admin) VALUES (?, ?, ?, ?)');
    $stmt->execute([$name, $email, $hash, $is_admin]);
    return $pdo->lastInsertId();
}

function getUserByEmail($email) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function createOrder($user_id, $cart) {
    global $pdo;
    $total = 0;
    foreach ($cart as $item) $total += $item['price'] * $item['qty'];
    $stmt = $pdo->prepare('INSERT INTO orders (user_id, total) VALUES (?, ?)');
    $stmt->execute([$user_id, $total]);
    $order_id = $pdo->lastInsertId();
    $stmt_item = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
    foreach ($cart as $p) {
        $stmt_item->execute([$order_id, $p['id'], $p['qty'], $p['price']]);
    }
    return $order_id;
}

function getOrdersByUser($user_id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function getAllOrders() {
    global $pdo;
    $stmt = $pdo->query('SELECT o.*, u.email as user_email FROM orders o LEFT JOIN users u ON o.user_id=u.id ORDER BY o.created_at DESC');
    return $stmt->fetchAll();
}

function updateOrderStatus($order_id, $status) {
    global $pdo;
    $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
    return $stmt->execute([$status, $order_id]);
}
