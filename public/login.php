<?php
require_once __DIR__ . '/../src/models.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$err = '';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email = trim(strtolower($_POST['email'] ?? ''));
  $password = $_POST['password'] ?? '';
  $user = getUserByEmail($email);
  if($user && password_verify($password, $user['password'])){
    $_SESSION['user'] = [
      'id' => $user['id'],
      'name' => $user['name'] ?? '',
      'email' => $user['email']
    ];
    header('Location: index.php'); exit;
  } else {
    $err = 'Invalid credentials.';
  }
}
require_once __DIR__ . '/_header.php';
?>
<div class="bg-white p-6 rounded shadow max-w-md mx-auto">
  <h2 class="text-xl font-semibold mb-4">Login</h2>
  <?php if($err): ?><div class="text-red-600 mb-2"><?php echo $err; ?></div><?php endif; ?>
  <form method="post" class="space-y-4">
    <input name="email" type="email" placeholder="Email" class="w-full p-2 border rounded" required>
    <input name="password" type="password" placeholder="Password" class="w-full p-2 border rounded" required>
    <div class="flex justify-end items-center">
      <button class="bg-indigo-600 text-white px-4 py-2 rounded">Login</button>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/_footer.php'; ?>