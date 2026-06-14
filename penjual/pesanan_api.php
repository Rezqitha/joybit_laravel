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
        // Ambil semua pesanan
        $result = $conn->query("SELECT * FROM pesanan ORDER BY id DESC");
        $pesanan = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pesanan[] = $row;
            }
        }
        echo json_encode(["status" => "success", "data" => $pesanan]);
        break;

    case 'POST':
        // Tambah pesanan (simulasi)
        $nama_pembeli = isset($data['nama_pembeli']) ? $data['nama_pembeli'] : '';
        $total_harga = isset($data['total_harga']) ? $data['total_harga'] : 0;
        $status = "Menunggu";
        $tanggal = date('Y-m-d');
        
        if(!empty($nama_pembeli) && !empty($total_harga)) {
            $stmt = $conn->prepare("INSERT INTO pesanan (nama_pembeli, total_harga, status, tanggal) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("siss", $nama_pembeli, $total_harga, $status, $tanggal);
            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Pesanan berhasil ditambahkan"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Gagal menambahkan pesanan"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
        }
        break;
        
    case 'PUT':
        // Update status pesanan
        $id = isset($data['id']) ? $data['id'] : '';
        $status = isset($data['status']) ? $data['status'] : '';
        
        if(!empty($id) && !empty($status)) {
            $stmt = $conn->prepare("UPDATE pesanan SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $status, $id);
            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Status pesanan diperbarui"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Gagal memperbarui pesanan"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "ID atau status kosong"]);
        }
        break;

    case 'DELETE':
        // Hapus pesanan
        $id = '';
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } else if (!empty($data['id'])) {
            $id = $data['id'];
        }

        if(!empty($id)) {
            $stmt = $conn->prepare("DELETE FROM pesanan WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Pesanan berhasil dihapus"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Gagal menghapus pesanan"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "ID diperlukan"]);
        }
        break;
}

$conn->close();
?>
