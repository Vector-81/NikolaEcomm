<?php
require_once __DIR__ . '/../src/models.php';
if (session_status() === PHP_SESSION_NONE) session_start();
// Admin system removed — redirect to main site
header('Location: index.php'); exit;

$action = $_POST['action'] ?? $_GET['action'] ?? null;
if($action==='add'){
    $data = [
        'category_id'=>$_POST['category_id']?:null,
        'title'=>$_POST['title']?:'Untitled',
        'description'=>$_POST['description']?:'',
        'price'=>$_POST['price']?:0,
        'image'=>null
    ];
    addProduct($data);
    header('Location: admin_products.php'); exit;
}
if($action==='delete'){
    $id = (int)($_GET['id']??0); deleteProduct($id); header('Location: admin_products.php'); exit;
}
$products = getProducts(500);
$categories = getCategories();
require_once __DIR__ . '/_header.php';
?>

<div class="flex items-center justify-between mb-6">
  <h1 class="text-2xl font-semibold">Admin — Products</h1>
  <a href="admin_orders.php" class="text-sm text-gray-600">Orders</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <div class="lg:col-span-2">
    <div class="bg-white p-4 rounded shadow space-y-3">
      <?php foreach($products as $p): ?>
        <div class="flex items-center justify-between border-b py-2">
          <div>
            <div class="font-medium"><?php echo htmlspecialchars($p['title']); ?></div>
            <div class="text-sm text-gray-500">$<?php echo number_format($p['price'],2); ?></div>
          </div>
          <div class="flex items-center gap-2">
            <a href="?action=delete&id=<?php echo $p['id']; ?>" class="text-red-600 text-sm">Delete</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <div>
    <div class="bg-white p-4 rounded shadow">
      <h3 class="font-semibold mb-3">Add Product</h3>
      <form method="post" class="space-y-3">
        <input type="hidden" name="action" value="add">
        <div>
          <label class="text-sm">Title</label>
          <input name="title" class="w-full p-2 border rounded" required>
        </div>
        <div>
          <label class="text-sm">Category</label>
          <select name="category_id" class="w-full p-2 border rounded">
            <option value="">— None —</option>
            <?php foreach($categories as $c): ?>
              <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="text-sm">Price</label>
          <input name="price" type="number" step="0.01" class="w-full p-2 border rounded" required>
        </div>
        <div>
          <label class="text-sm">Description</label>
          <textarea name="description" class="w-full p-2 border rounded"></textarea>
        </div>
        <div class="pt-2">
          <button class="bg-indigo-600 text-white px-4 py-2 rounded">Add</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/_footer.php'; ?>