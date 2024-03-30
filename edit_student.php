<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
        <?php 
        // Include your db_connect.php file to establish a database connection
        include 'db_connect.php';

        // Retrieve student details based on the ID passed through GET parameter
        if (isset($_GET['id'])) {
            $student_id = $_GET['id'];

            // Retrieve existing student details
            $sql_student_details = "SELECT * FROM StudentRecords WHERE student_id = $student_id";
            $result_student_details = $connection->query($sql_student_details);

            if ($result_student_details->num_rows > 0) {
                $student_details = $result_student_details->fetch_assoc();
        ?>
        <div class="card">
            <div class="card-header">
                <h2>Edit Student Details</h2>
            </div>
            <div class="card-body">
                <div id="editStudentForm"> <!-- Form container with unique ID -->
                    <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                    <!-- Input fields for editing student details -->
                    <div class="form-group">
                        <label for="full_name">Full Name:</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $student_details['full_name']; ?>" required>
                    </div>
                    <!-- Other input fields here... -->
                    <button type="button" id="saveStudentDetails" class="btn btn-save">Save</button> <!-- Use type="button" to prevent default form submission -->
                    <a href="student_details.php?id=<?php echo $student_id; ?>" class="btn btn-back">Back to Student Details</a>
                </div>
            </div>
        </div>
        <?php } else {
            echo "<div class='alert alert-danger' role='alert'>Student not found.</div>";
        }
        } else {
            echo "<div class='alert alert-danger' role='alert'>Invalid request.</div>";
        }

        // Close database connection
        $connection->close();
        ?>
    </div>

    <!-- JavaScript for AJAX -->
    <script>
        $(document).ready(function() {
            $('#saveStudentDetails').click(function() {
                // Collect form data
                var formData = $('#editStudentForm').serialize();

                // Make AJAX request
                $.ajax({
                    type: 'POST',
                    url: 'process_edit_student.php', // Specify your PHP script to handle form submission
                    data: formData,
                    success: function(response) {
                        // Handle response from the server
                        var result = JSON.parse(response);
                        if (result.success) {
                            // Display success message and do other actions if needed
                            alert('Student details updated successfully.');
                        } else {
                            // Display error message if update fails
                            alert('Error: ' + result.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle AJAX error
                        console.error(xhr.responseText);
                        alert('AJAX Error: ' + status + ' - ' + error);
                    }
                });
            });
        });
    </script>
</body>
</html>
