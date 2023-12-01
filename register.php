<?php

require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $username = $data['username'];
    $plainPassword = $data['password']; // Get the plain password

    // Hash the password on the server-side
    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashedPassword);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Registration successful']);
    } else {
        echo json_encode(['error' => 'Registration failed']);
    }

    $stmt->close();
}

$conn->close();
?>
