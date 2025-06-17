
</body>
</html>


<?php

// Include the necessary library for database connection (e.g., mysqli)
require_once 'mysqli.php'; // Assuming you have a mysqli.php file

// Define the database connection details
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a database connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check the connection
if ($conn->connect_error) {
  die('Connection failed: ' . $conn->connect_error);
}

// Get the search term from the form
$search_term = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

//  Escape the search term to prevent SQL injection
$search_term = $conn->real_escape_string($search_term);


// Perform the database query
$query = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $search_term . "%'"; // Replace your_table_name and your_column_name with your actual table and column names.  Use LIKE for partial matches.

$result = $conn->query($query);

?>
