<?php
require_once 'db_connect.php';
$res = $conn->query("SELECT * FROM produk LIMIT 5");
if($res) {
    while($r = $res->fetch_assoc()) {
        echo json_encode($r) . "\n";
    }
}
?>
