<?php
// Include your db_connect.php file to establish a database connection
include 'db_connect.php';

// Check if the student ID is provided in the URL
if(isset($_GET['id'])) {
    // Sanitize the input to prevent SQL injection
    $student_id = $_GET['id'];
    
    // Prepare the delete statement
    $sql_delete = "DELETE FROM StudentRecords WHERE student_id = ?";
    $stmt = $connection->prepare($sql_delete);
    $stmt->bind_param("i", $student_id);
    
    // Execute the delete statement
    if ($stmt->execute()) {
        // Redirect back to the dashboard or student list page after successful deletion
        header("Location: dashboard.php");
        exit();
    } else {
        // Handle any errors that occur during deletion
        echo "Error deleting record: " . $stmt->error;
    }
    
    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$connection->close();
?>
