<?php
require_once __DIR__ . '/../src/models.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$err = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $name = trim($_POST['name'] ?? '');
  $email = trim(strtolower($_POST['email'] ?? ''));
  $password = $_POST['password'] ?? '';
  $password2 = $_POST['password2'] ?? '';
  if(!$name || !$email || !$password) {
    $err = 'Please fill all fields.';
  } elseif ($password !== $password2) {
    $err = 'Passwords do not match.';
  } else {
    $existing = getUserByEmail($email);
    if($existing) {
      $err = 'User already exists with that email.';
    } else {
      $id = createUser($name, $email, $password, 0);
      $_SESSION['user'] = ['id'=>$id,'name'=>$name,'email'=>$email];
      header('Location: index.php'); exit;
    }
  }
}
require_once __DIR__ . '/_header.php';
?>
<div class="bg-white p-6 rounded shadow max-w-md mx-auto">
  <h2 class="text-xl font-semibold mb-4">Register</h2>
  <?php if($err): ?><div class="text-red-600 mb-2"><?php echo $err; ?></div><?php endif; ?>
  <form method="post" class="space-y-4">
    <input name="name" type="text" placeholder="Full name" class="w-full p-2 border rounded" required>
    <input name="email" type="email" placeholder="Email" class="w-full p-2 border rounded" required>
    <input name="password" type="password" placeholder="Password" class="w-full p-2 border rounded" required>
    <input name="password2" type="password" placeholder="Confirm password" class="w-full p-2 border rounded" required>
    <div class="flex justify-end items-center">
      <button class="bg-indigo-600 text-white px-4 py-2 rounded">Register</button>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/_footer.php'; ?>
