<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once '../db_connect.php';
// API Key Validation
$api_key = isset($_GET['api_key']) ? $_GET['api_key'] : '';
if ($api_key !== 'joybite123') {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Invalid API Key']);
    exit;
}


$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        // Ambil ulasan
        $id_produk = isset($_GET['id_produk']) ? $_GET['id_produk'] : '';
        if(!empty($id_produk)){
            $stmt = $conn->prepare("SELECT * FROM ulasan_produk WHERE id_produk = ? ORDER BY id DESC");
            $stmt->bind_param("i", $id_produk);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $conn->query("SELECT * FROM ulasan_produk ORDER BY id DESC");
        }
        
        $ulasan = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $ulasan[] = $row;
            }
        }
        echo json_encode(["status" => "success", "data" => $ulasan]);
        break;

    case 'POST':
        // Tambah ulasan
        $nama_pembeli = isset($data['nama_pembeli']) ? $data['nama_pembeli'] : '';
        $id_produk = isset($data['id_produk']) ? $data['id_produk'] : '';
        $komentar = isset($data['komentar']) ? $data['komentar'] : '';
        $rating = isset($data['rating']) ? $data['rating'] : 5;
        
        if(!empty($nama_pembeli) && !empty($id_produk) && !empty($komentar)) {
            $stmt = $conn->prepare("INSERT INTO ulasan_produk (nama_pembeli, id_produk, komentar, rating) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sisi", $nama_pembeli, $id_produk, $komentar, $rating);
            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Ulasan berhasil dikirim"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Gagal mengirim ulasan"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
        }
        break;
        
    case 'PUT':
        // Edit ulasan
        $id = isset($data['id']) ? $data['id'] : '';
        $komentar = isset($data['komentar']) ? $data['komentar'] : '';
        $rating = isset($data['rating']) ? $data['rating'] : '';
        
        if(!empty($id) && !empty($komentar)) {
            $stmt = $conn->prepare("UPDATE ulasan_produk SET komentar = ?, rating = ? WHERE id = ?");
            $stmt->bind_param("sii", $komentar, $rating, $id);
            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Ulasan berhasil diperbarui"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Gagal memperbarui ulasan"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "ID atau komentar kosong"]);
        }
        break;

    case 'DELETE':
        // Hapus ulasan
        $id = '';
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } else if (!empty($data['id'])) {
            $id = $data['id'];
        }

        if(!empty($id)) {
            $stmt = $conn->prepare("DELETE FROM ulasan_produk WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Ulasan berhasil dihapus"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Gagal menghapus ulasan"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "ID diperlukan"]);
        }
        break;
}

$conn->close();
?>
