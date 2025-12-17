<?php
require_once __DIR__ . '/../src/models.php';
require_once __DIR__ . '/_header.php';
$products = getProducts();
$categories = getCategories();
?>

<div class="flex items-center justify-between mb-6">
  <h1 class="text-2xl font-semibold">Products</h1>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
  <?php foreach($products as $p): ?>
    <div class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition-shadow">
      <?php if(!empty($p['image'])): ?>
        <a href="product.php?id=<?php echo $p['id']; ?>">
          <div class="h-40 overflow-hidden rounded">
            <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['title']); ?>" class="w-full h-40 object-cover">
          </div>
        </a>
      <?php else: ?>
        <div class="h-40 bg-gray-100 rounded flex items-center justify-center text-gray-400">Image</div>
      <?php endif; ?>
      <h3 class="mt-3 font-medium"><?php echo htmlspecialchars($p['title']); ?></h3>
      <div class="text-sm text-gray-500"><?php echo htmlspecialchars($p['category_name']); ?></div>
      <div class="mt-2 font-semibold">$<?php echo number_format($p['price'],2); ?></div>
      <div class="mt-4 flex items-center justify-between">
        <a href="product.php?id=<?php echo $p['id']; ?>" class="text-sm text-indigo-600 hover:underline">View</a>
        <form action="cart.php" method="post">
          <input type="hidden" name="action" value="add">
          <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
          <input type="hidden" name="qty" value="1">
          <button class="bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700 transition">Add</button>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/_footer.php'; ?>