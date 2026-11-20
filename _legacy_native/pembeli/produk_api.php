<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once '../db_connect.php';
// API Key Validasi
$api_key = isset($_GET['api_key']) ? $_GET['api_key'] : '';
if ($api_key !== 'joybite123') {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Invalid API Key']);
    exit;
}


$method = $_SERVER['REQUEST_METHOD'];
$data_masuk = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        // Mengambil semua produk
        $query = mysqli_query($conn, "SELECT * FROM produk");
        $data = [];
        
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
        echo json_encode($data);
        break;

    case 'POST':
        // Fitur pencarian produk berdasarkan keyword
        $keyword = isset($data_masuk['keyword']) ? $data_masuk['keyword'] : '';
        $keyword = mysqli_real_escape_string($conn, $keyword);

        $query = mysqli_query($conn, "SELECT * FROM produk WHERE nama_produk LIKE '%$keyword%' OR deskripsi LIKE '%$keyword%'");
        $data = [];
        
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
        echo json_encode($data);
        break;
}

$conn->close();
?>
