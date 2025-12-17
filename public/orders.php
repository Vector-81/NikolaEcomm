<?php
require_once __DIR__ . '/../src/models.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if(empty($_SESSION['user'])){ header('Location: login.php'); exit; }
$orders = getOrdersByUser($_SESSION['user']['id']);
require_once __DIR__ . '/_header.php';
?>
<div class="bg-white p-4 rounded shadow">
  <h2 class="text-xl font-semibold mb-4">My Orders</h2>
  <?php if(empty($orders)): ?><div>No orders yet.</div><?php else: ?>
    <div class="space-y-3">
      <?php foreach($orders as $o): ?>
        <div class="border p-3 rounded">
          <div class="flex justify-between">
            <div>
              <div class="font-medium">Order #<?php echo $o['id']; ?></div>
              <div class="text-sm text-gray-500"><?php echo $o['created_at']; ?></div>
            </div>
            <div class="text-sm font-semibold"><?php echo ucfirst($o['status']); ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/_footer.php'; ?>