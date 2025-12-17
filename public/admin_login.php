<?php
require_once __DIR__ . '/../src/models.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$err = '';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $email = $_POST['email'] ?? ''; $password = $_POST['password'] ?? '';
    $user = getUserByEmail($email);
    if($user && password_verify($password, $user['password']) && $user['is_admin']){
        $_SESSION['user'] = $user; header('Location: admin_products.php'); exit;
    } else $err = 'Invalid admin credentials.';
}
require_once __DIR__ . '/_header.php';
?>
<?php
// Admin system removed — redirect to main site
header('Location: index.php'); exit;
