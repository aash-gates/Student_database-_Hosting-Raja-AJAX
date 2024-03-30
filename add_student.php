<?php

// Start session management
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // If not logged in, redirect to the login page
    header("Location: index.php");
    exit(); // Stop further execution
}

// Continue with your existing code for editing student details

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
        // If insertion is successful, send success response
        echo json_encode(["success" => true]);
        exit();
    } else {
        // If there's an error, send error response
        echo json_encode(["success" => false, "error" => $connection->error]);
        exit();
    }

    // Close statement
    $stmt->close();
}

// Close database connection
$connection->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        /* Custom CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #FF416C, #FF4B2B);
            color: #fff;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }

        .card-header {
            background-color: rgba(255, 255, 255, 0.5);
            color: #333;
            font-weight: bold;
        }

        .card-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        .btn-save, .btn-back {
            background-color: #fff;
            color: #333;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            margin-right: 10px;
            text-decoration: none;
        }

        .btn-save:hover, .btn-back:hover {
            background-color: #eee;
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Add Student</h2>
            </div>
            <div class="card-body">
                <form id="addStudentForm" method="POST">
                    <div class="form-group">
                        <label for="full_name">Full Name:</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" required>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <select class="form-control" id="country_code" name="country_code" required>
                                    <option value="">Select Country Code</option>
                                    <?php
                                    // Include database connection
                                    include 'db_connect.php';

                                    // Fetch country codes from database
                                    $sql = "SELECT * FROM CountryCodes";
                                    $result = $connection->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['code'] . "'>" . $row['country'] . " (" . $row['code'] . ")</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number" pattern="[0-9]{10}" placeholder="Enter Phone Number" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth:</label>
                        <input type="date" class="form-control" id="dob" name="dob" max="<?php echo date('Y-m-d', strtotime('-2 years')); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="mother_tongue">Mother Tongue:</label>
                        <input type="text" class="form-control" id="mother_tongue" name="mother_tongue" required>
                    </div>
                    <div class="form-group">
                        <label for="blood_group">Blood Group:</label>
                        <select class="form-control" id="blood_group" name="blood_group" required>
                            <option value="">Select Blood Group</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="known_dust_allergies">Known Allergies:</label>
                        <select class="form-control" id="known_dust_allergies" name="known_dust_allergies" required>
                            <option value="">Select Allergy</option>
                            <?php
                            // Fetch allergies from database
                            $sql_allergies = "SELECT * FROM Allergies";
                            $result_allergies = $connection->query($sql_allergies);

                            if ($result_allergies->num_rows > 0) {
                                while ($row_allergies = $result_allergies->fetch_assoc()) {
                                    echo "<option value='" . $row_allergies['name'] . "'>" . $row_allergies['name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mother_name">Mother Name:</label>
                        <input type="text" class="form-control" id="mother_name" name="mother_name" required>
                    </div>
                    <div class="form-group">
                        <label for="father_name">Father Name:</label>
                        <input type="text" class="form-control" id="father_name" name="father_name" required>
                    </div>
                    <div class="form-group">
                        <label for="nationality">Nationality:</label>
                        <select class="form-control" id="nationality" name="nationality" required>
                            <option value="">Select Nationality</option>
                            <?php
                            // Fetch nationalities from database
                            $sql_nationalities = "SELECT * FROM Nationalities";
                            $result_nationalities = $connection->query($sql_nationalities);

                            if ($result_nationalities->num_rows > 0) {
                                while ($row_nationalities = $result_nationalities->fetch_assoc()) {
                                    echo "<option value='" . $row_nationalities['nationality'] . "'>" . $row_nationalities['nationality'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-save">Save</button>
                    <a href="dashboard.php" class="btn btn-back">Back to Dashboard</a>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            // Intercept form submission
            $('#addStudentForm').submit(function (e) {
                e.preventDefault(); // Prevent default form submission
                
                // Serialize form data
                var formData = $(this).serialize();

                // Submit form data via AJAX
                $.ajax({
                    type: 'POST',
                    url: 'process_add_student.php',
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
                        // Check if insertion was successful
                        if (response.success) {
                            alert('Student added successfully!');
                            window.location.href = 'dashboard.php'; // Redirect to dashboard
                        } else {
                            // If there's an error, display the error message
                            alert('Error: ' + response.error);
                        }
                    },
                    error: function () {
                        alert('An error occurred while processing the request.');
                    }
                });
            });
        });
    </script>
</body>
</html>
