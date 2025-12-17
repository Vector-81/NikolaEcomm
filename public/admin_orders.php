<?php
require_once __DIR__ . '/../src/models.php';
if (session_status() === PHP_SESSION_NONE) session_start();
 // Admin system removed — redirect to main site
 header('Location: index.php'); exit;
 
 // The following lines are removed as the admin UI is no longer needed
 // if(empty($_SESSION['user']) || !$_SESSION['user']['is_admin']){ header('Location: admin_login.php'); exit; }
 // if($_SERVER['REQUEST_METHOD']==='POST'){
 //     updateOrderStatus($_POST['order_id'], $_POST['status']);
 // }
 // $orders = getAllOrders();
 // require_once __DIR__ . '/_header.php';
?>
<div class="bg-white p-4 rounded shadow">
  <h2 class="text-xl font-semibold mb-4">Orders</h2>
  <?php if(empty($orders)): ?><div>No orders yet.</div><?php else: ?>
    <div class="space-y-3">
      <?php foreach($orders as $o): ?>
        <div class="border p-3 rounded">
          <div class="flex justify-between items-center">
            <div>
              <div class="font-medium">Order #<?php echo $o['id']; ?></div>
              <div class="text-sm text-gray-500"><?php echo $o['user_email']?:'Guest'; ?> · <?php echo $o['created_at']; ?></div>
            </div>
            <div>
              <form method="post" class="flex items-center gap-2">
                <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                <select name="status" class="p-1 border rounded">
                  <?php foreach(['pending','processing','shipped','delivered'] as $s): ?>
                    <option value="<?php echo $s; ?>" <?php if($o['status']==$s) echo 'selected'; ?>><?php echo ucfirst($s); ?></option>
                  <?php endforeach; ?>
                </select>
                <button class="bg-indigo-600 text-white px-3 py-1 rounded">Update</button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/_footer.php'; ?>