<?php
// Include your db_connect.php file to establish a database connection
include 'db_connect.php';

// Initialize response array
$response = array('success' => false, 'error' => '');

// Check if form data is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from POST data
    $new_username = $_POST["new_username"];
    $new_password = $_POST["new_password"];

    // Check if username already exists
    $check_username_sql = "SELECT * FROM LoginCredentials WHERE username = ?";
    $check_stmt = $connection->prepare($check_username_sql);
    $check_stmt->bind_param("s", $new_username);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Set error message for existing username
        $response['error'] = 'Username already exists';
    } else {
        // Insert new user into LoginCredentials table
        $insert_sql = "INSERT INTO LoginCredentials (username, password_hash) VALUES (?, ?)";
        $insert_stmt = $connection->prepare($insert_sql);
        $insert_stmt->bind_param("ss", $new_username, $new_password);
        $insert_stmt->execute();
        
        // Set success message for new user creation
        $response['success'] = true;
        $response['message'] = 'New user added successfully';
    }
}

// Close database connection
$connection->close();

// Send JSON response
echo json_encode($response);
?>
