<?php
session_start(); // Start session to access session variables

// Include your db_connect.php file to establish a database connection
include 'db_connect.php';

// Initialize variables to store user input
$username = $password = "";

// Check if form data is received via POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from form data
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepare SQL statement to retrieve user data
    $sql = "SELECT * FROM LoginCredentials WHERE username = ? AND password_hash = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists and credentials are correct
    if ($result->num_rows > 0) {
        // Set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;

        // Send success response to AJAX request
        echo 'success';
    } else {
        // Send error response to AJAX request
        echo 'Invalid username or password';
    }
} else {
    // Send error response if request method is not POST
    echo 'Invalid request method';
}
?>
