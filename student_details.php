<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        /* CSS styles here */
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

        .student-details p {
            margin-bottom: 10px;
            font-size: 18px;
        }

        .student-details h2 {
            margin-bottom: 20px;
        }

        .btn-edit, .btn-delete, .btn-back {
            background-color: #fff;
            color: #333;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            margin-right: 10px;
            text-decoration: none;
        }

        .btn-edit:hover, .btn-delete:hover, .btn-back:hover {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Student Details</h2>
            </div>
            <div class="card-body student-details">
                <?php 
                // Include your db_connect.php file to establish a database connection
                include 'db_connect.php';

                // Retrieve student details based on the ID passed through GET parameter
                if (isset($_GET['id'])) {
                    $student_id = $_GET['id'];
                    
                    // Prepare the SQL statement to prevent SQL injection
                    $sql_student_details = $connection->prepare("SELECT * FROM StudentRecords WHERE student_id = ?");
                    $sql_student_details->bind_param("i", $student_id);
                    $sql_student_details->execute();
                    $result_student_details = $sql_student_details->get_result();
                    
                    if ($result_student_details->num_rows > 0) {
                        $student_details = $result_student_details->fetch_assoc();
                    } else {
                        echo "<p>No student details found for ID: " . $student_id . "</p>";
                    }
                } else {
                    echo "<p>Invalid request.</p>";
                }
                ?>

                <?php if (isset($student_details)): ?>
                    <h2><?php echo $student_details['full_name']; ?></h2>
                    <?php foreach ($student_details as $key => $value): ?>
                        <?php if ($key != 'student_id' && $key != 'full_name'): ?>
                            <p><strong><?php echo ucwords(str_replace('_', ' ', $key)); ?>:</strong> <?php echo $value; ?></p>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <a href="edit_student.php?id=<?php echo $student_id; ?>" class="btn btn-edit">Edit</a>
                    <button class="btn btn-delete" onclick="deleteStudent(<?php echo $student_id; ?>)">Delete</button>
                    <a href="dashboard.php" class="btn btn-back">Back to Dashboard</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        // Function to delete a student record asynchronously
        function deleteStudent(studentId) {
            if (confirm('Are you sure you want to delete this student record?')) {
                $.ajax({
                    url: 'delete_student.php?id=' + studentId,
                    type: 'GET',
                    success: function(response) {
                        // Redirect to the dashboard after successful deletion
                        window.location.href = 'dashboard.php';
                    },
                    error: function(xhr, status, error) {
                        // Display an error message if deletion fails
                        alert('Error deleting student record. Please try again.');
                    }
                });
            }
        }
    </script>
</body>
</html>
