<?php
require_once __DIR__ . '/../src/models.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['user'])) {
  header('Location: login.php'); exit;
}
$err = '';
$uploadError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $imagePath = '';
  // handle file upload (input name: image_file)
  if (!empty($_FILES['image_file']['name']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif'];
    if (in_array($ext, $allowed)) {
      $fn = uniqid('img_', true) . '.' . $ext;
      $dest = $uploadDir . $fn;
      if (move_uploaded_file($_FILES['image_file']['tmp_name'], $dest)) {
        $imagePath = 'uploads/' . $fn;
      } else {
        $uploadError = 'Failed to move uploaded file.';
      }
    } else {
      $uploadError = 'Invalid file type.';
    }
  }

  // fallback to external URL if provided
  $imageUrl = trim($_POST['image_url'] ?? '');
  if (!$imagePath && $imageUrl) {
    $imagePath = $imageUrl;
  }

  $data = [
    'category_id' => $_POST['category_id'] ?? null,
    'title' => trim($_POST['title'] ?? ''),
    'description' => trim($_POST['description'] ?? ''),
    'price' => floatval($_POST['price'] ?? 0),
    'image' => $imagePath
  ];
  if (!$data['title'] || $data['price'] <= 0) {
    $err = 'Please provide a title and valid price.';
  } else {
    $id = addProduct($data);
    header('Location: product.php?id=' . $id); exit;
  }
}
$categories = getCategories();
require_once __DIR__ . '/_header.php';
?>
<div class="bg-white p-6 rounded shadow max-w-md mx-auto">
  <h2 class="text-xl font-semibold mb-4">Add Product</h2>
  <?php if($err): ?><div class="text-red-600 mb-2"><?php echo $err; ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data" class="space-y-4">
    <label class="block text-sm">Category</label>
    <select name="category_id" class="w-full p-2 border rounded">
      <option value="">-- None --</option>
      <?php foreach($categories as $c): ?>
        <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
      <?php endforeach; ?>
    </select>
    <input name="title" type="text" placeholder="Title" class="w-full p-2 border rounded" required>
    <textarea name="description" placeholder="Description" class="w-full p-2 border rounded"></textarea>
    <input name="price" type="number" step="0.01" placeholder="Price" class="w-full p-2 border rounded" required>
    <div>
      <label class="text-sm">Image (file)</label>
      <input type="file" name="image_file" accept="image/*" class="w-full p-2 border rounded">
      <div class="text-xs text-gray-500 mt-1">Or provide an external image URL below</div>
      <input name="image_url" type="url" placeholder="https://..." class="w-full p-2 border rounded mt-1">
      <?php if(!empty($uploadError)): ?><div class="text-red-600 text-sm mt-1"><?php echo $uploadError; ?></div><?php endif; ?>
    </div>
    <div class="flex justify-end items-center">
      <button class="bg-indigo-600 text-white px-4 py-2 rounded">Add Product</button>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/_footer.php'; ?>
