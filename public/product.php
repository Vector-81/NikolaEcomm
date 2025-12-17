<?php
require_once __DIR__ . '/../src/models.php';
require_once __DIR__ . '/_header.php';
$id = $_GET['id'] ?? null;
if(!$id) { header('Location: /'); exit; }
$p = getProduct($id);
if(!$p) { echo '<div>Product not found</div>'; require_once __DIR__ . '/_footer.php'; exit; }
?>

<div class="bg-white p-6 rounded shadow">
  <div class="flex gap-6">
    <?php if(!empty($p['image'])): ?>
      <div class="w-1/3">
        <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['title']); ?>" class="w-full h-48 object-cover rounded">
      </div>
    <?php else: ?>
      <div class="w-1/3 bg-gray-100 h-48 flex items-center justify-center text-gray-400">Image</div>
    <?php endif; ?>
    <div class="flex-1">
      <h2 class="text-2xl font-semibold"><?php echo htmlspecialchars($p['title']); ?></h2>
      <div class="text-sm text-gray-500"><?php echo htmlspecialchars($p['category_name']); ?></div>
      <p class="mt-4 text-gray-700"><?php echo nl2br(htmlspecialchars($p['description'])); ?></p>
      <div class="mt-4 font-bold text-lg">$<?php echo number_format($p['price'],2); ?></div>
      <form action="cart.php" method="post" class="mt-4 flex items-center gap-2">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
        <input type="number" name="qty" value="1" min="1" class="w-20 p-2 border rounded">
        <button class="bg-indigo-600 text-white px-4 py-2 rounded">Add to cart</button>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/_footer.php'; ?>