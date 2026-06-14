<?php
$files = [
    'pembeli/cart_api.php',
    'pembeli/produk_api.php',
    'pembeli/ulasan_api.php',
    'penjual/dessert_api.php',
    'penjual/pesanan_api.php',
    'admin/validasi_api.php',
    'admin/user_api.php'
];

$auth_code = "\n// API Key Validation\n\$api_key = isset(\$_GET['api_key']) ? \$_GET['api_key'] : '';\nif (\$api_key !== 'joybite123') {\n    http_response_code(401);\n    echo json_encode(['status' => 'error', 'message' => 'Invalid API Key']);\n    exit;\n}\n";

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, 'API Key Validation') === false) {
            // Insert after db_connect.php
            $content = preg_replace('/(require_once [\'"]\.\.\/db_connect\.php[\'"];)/', "$1" . $auth_code, $content);
            file_put_contents($file, $content);
            echo "Patched $file\n";
        } else {
            echo "Already patched $file\n";
        }
    } else {
        echo "File not found $file\n";
    }
}
?>
