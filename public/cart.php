<?php
require_once __DIR__ . '/../src/models.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$action = $_POST['action'] ?? $_GET['action'] ?? null;
if($action === 'add'){
  $id = (int)($_POST['id'] ?? 0);
  $qty = max(1,(int)($_POST['qty'] ?? 1));
  $p = getProduct($id);
  if(!$p) {
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])==='xmlhttprequest'){
      header('Content-Type: application/json'); echo json_encode(['success'=>false]); exit;
    }
    header('Location: /'); exit;
  }
  if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
  // merge
  $found = false;
  foreach($_SESSION['cart'] as &$item){
    if($item['id']==$p['id']){ $item['qty'] += $qty; $found = true; break; }
  }
  if(!$found) $_SESSION['cart'][] = ['id'=>$p['id'],'title'=>$p['title'],'price'=>$p['price'],'qty'=>$qty];
  // If AJAX request, return JSON with item title and cart count
  if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])==='xmlhttprequest'){
    $count = isset($_SESSION['cart'])? array_sum(array_column($_SESSION['cart'],'qty')):0;
    header('Content-Type: application/json');
    echo json_encode(['success'=>true,'item_title'=>$p['title'],'cart_count'=>$count]); exit;
  }
  header('Location: cart.php'); exit;
}
if($action === 'remove'){
    $id = (int)($_GET['id'] ?? 0);
    if(isset($_SESSION['cart'])){
        $_SESSION['cart'] = array_values(array_filter($_SESSION['cart'], function($i) use($id){ return $i['id']!=$id; }));
    }
    header('Location: cart.php'); exit;
}
require_once __DIR__ . '/_header.php';
$cart = $_SESSION['cart'] ?? [];
$total = 0; foreach($cart as $c) $total += $c['price']*$c['qty'];
?>

<div class="bg-white p-6 rounded shadow">
  <h2 class="text-xl font-semibold mb-4">Cart</h2>
  <?php if(empty($cart)): ?>
    <div class="text-gray-500">Your cart is empty.</div>
  <?php else: ?>
    <div class="space-y-4">
      <?php foreach($cart as $item): ?>
        <div class="flex items-center justify-between border-b pb-2">
          <div>
            <div class="font-medium"><?php echo htmlspecialchars($item['title']); ?></div>
            <div class="text-sm text-gray-500">$<?php echo number_format($item['price'],2); ?> × <?php echo $item['qty']; ?></div>
          </div>
          <div class="flex items-center gap-2">
            <a href="cart.php?action=remove&id=<?php echo $item['id']; ?>" class="text-sm text-red-600">Remove</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="mt-4 flex items-center justify-between">
      <div class="font-semibold">Total: $<?php echo number_format($total,2); ?></div>
      <a href="checkout.php" class="bg-indigo-600 text-white px-4 py-2 rounded">Checkout</a>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/_footer.php'; ?>