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
$data_masuk = json_decode(file_get_contents("php://input"));

switch ($method) {
    case 'GET':
        // Read data dessert
        $sql = "SELECT id, nama_produk AS nama_dessert, harga, stok FROM produk";
        $result = $conn->query($sql);
        
        $dessert_list = array();
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($dessert_list, $row);
            }
            echo json_encode([
                "status" => "success",
                "data" => $dessert_list
            ]);
        } else {
            echo json_encode([
                "status" => "empty",
                "message" => "dessert tidak ada"
            ]);
        }
        break;

    case 'POST':
        // Create (Add dessert)
        if(!empty($data_masuk->nama_dessert) && !empty($data_masuk->harga) && !empty($data_masuk->stok)) {
            $nama_dessert = $data_masuk->nama_dessert;
            $harga = $data_masuk->harga;
            $stok = $data_masuk->stok;
            $sql = "INSERT INTO produk (nama_produk, harga, stok, deskripsi, kategori, rating, jumlah_ulasan, gambar) VALUES ('$nama_dessert', '$harga', '$stok', '', 'Dessert', 5.0, 0, '')";
            if($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "dessert berhasil ditambahkan"]);
            } else {
                echo json_encode(["status" => "error", "message" => "dessert gagal ditambahkan: " . $conn->error]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "data tidak lengkap"]);
        }
        break;

    case 'PUT':
        // Update dessert
        if (!empty($data_masuk->id) && !empty($data_masuk->nama_dessert) && !empty($data_masuk->harga) && !empty($data_masuk->stok)) {
            $id = $data_masuk->id;
            $nama = $data_masuk->nama_dessert;
            $harga = $data_masuk->harga;
            $stok = $data_masuk->stok;

            $sql = "UPDATE produk SET nama_produk='$nama', harga='$harga', stok='$stok' WHERE id=$id";

            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Data dessert berhasil diperbarui!"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Gagal memperbarui: " . $conn->error]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Data tidak lengkap!"]);
        }
        break;

    case 'DELETE':
        // Delete dessert
        // Karena di fetch JS dikirim lewat URL ?id=..., kita ambil lewat $_GET
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "DELETE FROM produk WHERE id = $id";

            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Dessert berhasil dihapus!"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Gagal menghapus: " . $conn->error]);
            }
        } else {
            // Bisa jadi dikirim lewat body json
            if (!empty($data_masuk->id)) {
                $id = $data_masuk->id;
                $sql = "DELETE FROM produk WHERE id = $id";
    
                if ($conn->query($sql) === TRUE) {
                    echo json_encode(["status" => "success", "message" => "Dessert berhasil dihapus!"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Gagal menghapus: " . $conn->error]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "ID tidak ditemukan"]);
            }
        }
        break;
}

$conn->close();
?>
