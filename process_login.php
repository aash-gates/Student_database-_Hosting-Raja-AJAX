<?php
session_start();

include 'db_connect.php';

$username = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM LoginCredentials WHERE username = ? AND password_hash = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        echo 'success';
    } else {
        echo 'Invalid username or password';
    }
} else {
    echo 'Invalid request method';
}
?>
