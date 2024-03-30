<?php
// Include db_connect.php to establish a database connection
include 'db_connect.php';

// Retrieve a random quote from the database
$sql_quote = "SELECT quote FROM Quotes ORDER BY RAND() LIMIT 1";
$result_quote = $connection->query($sql_quote);

if ($result_quote->num_rows > 0) {
    $row = $result_quote->fetch_assoc();
    echo $row['quote'];
} else {
    echo "No quotes found.";
}
?>
