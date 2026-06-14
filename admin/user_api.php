<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once '../db_connect.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Mengambil semua data pembeli (Read)
        $query = "SELECT id_user, username FROM pembeli";
        $result = mysqli_query($conn, $query);
        $data = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            echo json_encode(["status" => "success", "data" => $data]);
        } else {
            echo json_encode(["status" => "empty", "message" => "Tidak ada data pembeli"]);
        }
        break;

    case 'POST':
        // Login pembeli (Create/Auth session)
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $username = isset($data['username']) ? $data['username'] : '';
        $password = isset($data['password']) ? $data['password'] : '';

        if (empty($username) || empty($password)) {
            echo json_encode([
                "status" => "error",
                "message" => "Username dan Password tidak boleh kosong"
            ]);
            exit;
        }

        $query = "SELECT * FROM pembeli WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            echo json_encode([
                "status" => "success",
                "message" => "Login Berhasil",
                "data" => [
                    "id_user" => $user['id_user'],
                    "username" => $user['username']
                ]
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Username atau Password salah"
            ]);
        }
        break;
}

$conn->close();
?>
