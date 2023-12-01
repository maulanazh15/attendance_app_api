<?php

require_once 'db_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $userId = $data['user_id'];

    // Update the user's token to null or an empty string for logout
    $updateTokenStmt = $conn->prepare("UPDATE users SET token = NULL WHERE id = ?");
    $updateTokenStmt->bind_param("i", $userId);
    $updateTokenStmt->execute();
    $updateTokenStmt->close();

    echo json_encode(['message' => 'Logout successful']);
}

$conn->close();

?>
