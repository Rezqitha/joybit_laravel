<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../db_connect.php';

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        // Ambil semua produk
        $result = $conn->query("SELECT * FROM validasi_prdouk ORDER BY id_validasi DESC");
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        echo json_encode($products);
        break;

    case 'POST':
        // Tambah produk baru
        $nama = $data['nama_produk'];
        $toko = $data['nama_toko'];
        $desc = $data['deskripsi'];
        
        $status = "Menunggu Validasi";
        $dibuat_pada = date('Y-m-d');
        
        $stmt = $conn->prepare("INSERT INTO validasi_prdouk (nama_produk, nama_toko, status, deskripsi, dibuat_pada) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nama, $toko, $status, $desc, $dibuat_pada);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "id" => $stmt->insert_id]);
        } else {
            echo json_encode(["status" => "error"]);
        }
        break;
        
    case 'PUT':
        // Update status validasi
        $id = $data['id_validasi'];
        $new_status = $data['status'];
        
        $stmt = $conn->prepare("UPDATE validasi_prdouk SET status = ? WHERE id_validasi = ?");
        $stmt->bind_param("si", $new_status, $id);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Status updated"]);
        } else {
            echo json_encode(["status" => "error"]);
        }
        break;

    case 'DELETE':
        // Hapus produk
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } else if (!empty($data['id_validasi'])) {
            $id = $data['id_validasi'];
        } else {
            echo json_encode(["status" => "error", "message" => "ID required"]);
            break;
        }
        
        $stmt = $conn->prepare("DELETE FROM validasi_prdouk WHERE id_validasi = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(["status" => "deleted"]);
        } else {
            echo json_encode(["status" => "error"]);
        }
        break;
}

$conn->close();
?>
