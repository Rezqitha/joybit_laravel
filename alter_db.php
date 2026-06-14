<?php
require_once 'db_connect.php';
$sql = "ALTER TABLE produk ADD COLUMN stok INT NOT NULL DEFAULT 40";
if ($conn->query($sql) === TRUE) {
    echo "Stok added to produk successfully\n";
} else {
    echo "Error adding stok: " . $conn->error . "\n";
}
?>
