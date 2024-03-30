<?php
// Database configuration
$db_host = ""; // Server: Hostname of the database server
$db_username = ""; // Username: Username for database access
$db_password = ""; // Password: Password for database access
$db_name = ""; // Name: Name of the database (without file extension)
$db_port = ""; // Port number: Port number for the database connection

// Attempt to establish a connection to the database
$connection = mysqli_connect($db_host, $db_username, $db_password, $db_name, $db_port);

// Check if the connection was successful
if (!$connection) {
    // If connection failed, display error message and terminate script
    die("Connection failed: " . mysqli_connect_error());
}
?>

