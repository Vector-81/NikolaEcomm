<?php
// Script to populate missing product images with internet placeholder images.
// Usage: open in browser (http://.../public/populate_images.php) or run via PHP CLI.

require_once __DIR__ . '/../src/models.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Optional simple guard to avoid accidental runs: pass ?run=1
if(php_sapi_name() !== 'cli' && empty($_GET['run'])){
    echo "This script will update products without images.\n";
    echo "Add ?run=1 to the URL to execute (or run via CLI).\n";
    exit;
}

$products = getProducts(1000);
$updated = [];
foreach($products as $p){
    if(!empty($p['image'])) continue;
    $id = $p['id'];
    // Use picsum.photos with a seed for deterministic images per product
    $seed = rawurlencode('product-'.$id.'-'.($p['title'] ?? ''));
    $img = "https://picsum.photos/seed/{$seed}/800/600";
    $data = [
        'category_id' => $p['category_id'] ?? null,
        'title' => $p['title'] ?? 'Untitled',
        'description' => $p['description'] ?? '',
        'price' => $p['price'] ?? 0,
        'image' => $img
    ];
    updateProduct($id, $data);
    $updated[] = ['id'=>$id,'title'=>$p['title'],'image'=>$img];
}

header('Content-Type: application/json');
echo json_encode(['updated_count'=>count($updated),'updated'=>$updated], JSON_PRETTY_PRINT);
