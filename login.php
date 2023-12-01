<?php

require_once 'db_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $username = $data['username'];
    $password = $data['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($userId, $dbUsername, $hashedPassword);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($password, $hashedPassword)) {
        // Generate a random token
        $token = bin2hex(random_bytes(32)); // Adjust the length as needed

        // Update the user's token in the database
        $updateTokenStmt = $conn->prepare("UPDATE users SET token = ? WHERE id = ?");
        $updateTokenStmt->bind_param("si", $token, $userId);
        $updateTokenStmt->execute();
        $updateTokenStmt->close();

        echo json_encode(['message' => 'Login successful', 'user_id' => $userId, 'username' => $dbUsername, 'token' => $token]);
    } else {
        echo json_encode(['error' => 'Invalid username or password']);
    }
}

$conn->close();

?>
