<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
    <div class="container p-4" style="max-width: 1600px;">
        <div class="text-right mb-4">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
        <div class="text-center mb-4">
            <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2><br>
            <h4>A Program created by Aashik</h4>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">Current Time</h5>
                    <div class="card-body" id="time-info">
                        <!-- Time information will be updated dynamically -->
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">Thought for the Day</h5>
                    <div class="card-body" id="quote-info">
                        <!-- Quote information will be updated dynamically -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h2 class="card-header">List of Students</h2>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Student ID</th>
                                    </tr>
                                </thead>
                                <tbody id="student-table-body">
                                    <?php
                                    // Include db_connect.php to establish a database connection
                                    include 'db_connect.php';

                                    // Pagination variables
                                    $results_per_page = 10;
                                    $sql_students = "SELECT full_name, student_id FROM StudentRecords";
                                    $result_students = $connection->query($sql_students);
                                    $num_rows = $result_students->num_rows;
                                    $num_pages = ceil($num_rows / $results_per_page);

                                    // Get current page from URL query string
                                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                    $start_index = ($page - 1) * $results_per_page;

                                    // Retrieve students for the current page
                                    $sql_page = "SELECT full_name, student_id FROM StudentRecords LIMIT $start_index, $results_per_page";
                                    $result_page = $connection->query($sql_page);

                                    if ($result_page->num_rows > 0) {
                                        while ($row = $result_page->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td><a href='student_details.php?id=" . $row['student_id'] . "'>" . $row['full_name'] . "</a></td>";
                                            echo "<td>" . $row['student_id'] . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='2'>No students found.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination links -->
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <?php
                                for ($i = 1; $i <= $num_pages; $i++) {
                                    echo "<li class='page-item" . ($i == $page ? ' active' : '') . "'><a class='page-link' href='?page=$i'>$i</a></li>";
                                }
                                ?>
                            </ul>
                            <!-- Loading spinner -->
                            <div id="loading-spinner" class="spinner-border text-primary" role="status" style="display: none;">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </nav>
                        <a href="add_student.php" class="btn btn-primary">Add Student</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for AJAX -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        // Function to fetch time information via AJAX
        function getTime() {
            $.ajax({
                url: 'get_time.php',
                success: function(response) {
                    $('#time-info').text('Current Time: ' + response);
                }
            });
        }

        // Function to fetch quote information via AJAX
        function getQuote() {
            $.ajax({
                url: 'get_quote.php',
                success: function(response) {
                    $('#quote-info').text(response);
                }
            });
        }

        // Function to fetch paginated student records
        function getStudents(page) {
            // Show loading spinner
            $('#loading-spinner').show();

            $.ajax({
        // Initial call to fetch time and quote information
        getTime();
        getQuote();

        // Update time every second
        setInterval(getTime, 1000);

        // Update quote every 30 seconds
        setInterval(getQuote, 30000);
    </script>
</body>

</html>
