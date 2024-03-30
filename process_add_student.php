<?php

// Start session management
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // If not logged in, return an error response
    echo json_encode(array("success" => false, "error" => "User not logged in"));
    exit(); // Stop further execution
}

// Continue with your existing code for adding student details

// Include your db_connect.php file to establish a database connection
include 'db_connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form inputs
    $full_name = $_POST['full_name'];
    $phone_number = $_POST['country_code'] . $_POST['phone_number'];
    $dob = $_POST['dob'];
    $mother_tongue = $_POST['mother_tongue'];
    $blood_group = $_POST['blood_group'];
    $known_dust_allergies = $_POST['known_dust_allergies'];
    $mother_name = $_POST['mother_name'];
    $father_name = $_POST['father_name'];
    $nationality = $_POST['nationality'];

    // Insert the student details into the database
    $sql_insert = "INSERT INTO StudentRecords (full_name, phone_number, dob, mother_tongue, blood_group, known_dust_allergies, mother_name, father_name, nationality) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Prepare and bind the statement
    $stmt = $connection->prepare($sql_insert);
    $stmt->bind_param("sssssssss", $full_name, $phone_number, $dob, $mother_tongue, $blood_group, $known_dust_allergies, $mother_name, $father_name, $nationality);
    
    // Execute the statement
    if ($stmt->execute() === TRUE) {
        // If insertion is successful, return a success response
        echo json_encode(array("success" => true));
    } else {
        // If there's an error, return an error response
        echo json_encode(array("success" => false, "error" => "Error: " . $sql_insert . "<br>" . $connection->error));
    }

    // Close statement
    $stmt->close();
}

// Close database connection
$connection->close();
?>
