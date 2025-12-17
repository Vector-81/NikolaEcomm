<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Mini Shop</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
<nav class="bg-white shadow-sm">
  <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between">
    <?php $current = basename($_SERVER['SCRIPT_NAME'] ?? ''); ?>
    <div class="w-8 flex items-center justify-center">
      <?php if($current !== 'index.php'): ?>
        <a href="index.php" class="text-xl text-gray-600 hover:text-black-900">&lt;</a>
      <?php else: ?>
        <span class="inline-block w-4"></span>
      <?php endif; ?>
    </div>
    <div class="flex-1 text-center">
      <a href="index.php" class="font-semibold text-lg">Mini Shop</a>
    </div>
    <div class="space-x-4">
      <a href="index.php" class="text-sm">Products</a>
      <a href="cart.php" class="text-sm">Cart (<span id="cart-count"><?php echo isset($_SESSION['cart'])? array_sum(array_column($_SESSION['cart'],'qty')):0; ?></span>)</a>
      <?php if(!empty($_SESSION['user'])): ?>
        <a href="add_product.php" class="text-sm">Add Product</a>
        <a href="orders.php" class="text-sm">My Orders</a>
        <a href="logout.php" class="text-sm">Logout</a>
      <?php else: ?>
        <a href="login.php" class="text-sm">Login</a>
        <a href="register.php" class="text-sm">Register</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<main class="max-w-5xl mx-auto p-6">
