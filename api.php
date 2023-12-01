<?php
require_once 'db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Mendapatkan daftar kehadiran
    $result = $conn->query("SELECT * FROM attendance ORDER BY created_at DESC");
    $attendance = [];
    while ($row = $result->fetch_assoc()) {
        $attendance[] = $row;
    }
    echo json_encode($attendance);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Menambahkan kehadiran baru
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'];
    $status = $data['status'];
    $conn->query("INSERT INTO attendance (name, status) VALUES ('$name', '$status')");
    echo json_encode(['message' => 'Attendance added successfully']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Mengedit kehadiran
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $status = $data['status'];
    $conn->query("UPDATE attendance SET status='$status' WHERE id=$id");
    echo json_encode(['message' => 'Attendance updated successfully']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Menghapus kehadiran
    $id = $_GET['id'];
    $conn->query("DELETE FROM attendance WHERE id=$id");
    echo json_encode(['message' => 'Attendance deleted successfully']);
}

$conn->close();
