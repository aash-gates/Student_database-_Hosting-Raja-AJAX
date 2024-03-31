<?php
// Include db_connect.php to establish a database connection
include 'db_connect.php';

// Pagination variables
$results_per_page = 10;

// Get current page from URL query string
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_index = ($page - 1) * $results_per_page;

// Retrieve students for the current page
$sql_page = "SELECT full_name, student_id FROM StudentRecords LIMIT $start_index, $results_per_page";
$result_page = $connection->query($sql_page);
