<?php
require_once __DIR__ . '/../src/models.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$cart = $_SESSION['cart'] ?? [];
if(empty($cart)) { header('Location: cart.php'); exit; }
if($_SERVER['REQUEST_METHOD']==='POST'){
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    if(!$email || !$name){ $error = 'Name and email required.'; }
    else {
        $user = getUserByEmail($email);
        if(!$user){
            $uid = createUser($name, $email, bin2hex(random_bytes(4)) );
            $user_id = $uid;
        } else {
            $user_id = $user['id'];
        }
        $order_id = createOrder($user_id, $cart);
        unset($_SESSION['cart']);
        header('Location: orders.php'); exit;
    }
}
require_once __DIR__ . '/_header.php';
$total = 0; foreach($cart as $c) $total += $c['price']*$c['qty'];
?>

<div class="bg-white p-6 rounded shadow">
  <h2 class="text-xl font-semibold mb-4">Checkout</h2>
  <?php if(!empty($error)): ?><div class="text-red-600 mb-4"><?php echo $error; ?></div><?php endif; ?>
  <form method="post" class="space-y-4">
    <div>
      <label class="block text-sm text-gray-600">Name</label>
      <input name="name" class="w-full p-2 border rounded" required>
    </div>
    <div>
      <label class="block text-sm text-gray-600">Email</label>
      <input name="email" type="email" class="w-full p-2 border rounded" required>
    </div>
    <div class="pt-4 flex items-center justify-between">
      <div class="font-semibold">Total: $<?php echo number_format($total,2); ?></div>
      <button class="bg-indigo-600 text-white px-4 py-2 rounded">Place Order</button>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/_footer.php'; ?>