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
        /* Custom CSS styles here */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            animation: changeBackground 10s linear infinite;
        }

        @keyframes changeBackground {
            0% {
                background-color: rgb(255, 0, 0); /* Red */
            }

            25% {
                background-color: rgb(0, 255, 0); /* Green */
            }

            50% {
                background-color: rgb(0, 0, 255); /* Blue */
            }

            75% {
                background-color: rgb(255, 255, 0); /* Yellow */
            }

            100% {
                background-color: rgb(255, 0, 255); /* Magenta */
            }
        }

        .container {
            margin-top: 20px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .logout-btn {
            margin-bottom: 20px;
        }

        .card {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
            color: #333;
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .card-body {
            padding: 20px;
        }

        .quote {
            font-size: 18px;
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
                        echo "<h2>" . $student_details['full_name'] . "</h2>";
                        foreach ($student_details as $key => $value) {
                            if ($key != 'student_id' && $key != 'full_name') {
                                echo "<p><strong>" . ucwords(str_replace('_', ' ', $key)) . ":</strong> " . $value . "</p>";
                            }
                        }
                        echo '<a href="edit_student.php?id=' . $student_id . '" class="btn btn-primary">Edit</a>';
                        echo '<button class="btn btn-danger" onclick="deleteStudent(' . $student_id . ')">Delete</button>';
                    } else {
                        echo "<p>No student details found for ID: " . $student_id . "</p>";
                    }
                } else {
                    echo "<p>Invalid request.</p>";
                }
                ?>
                <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
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
