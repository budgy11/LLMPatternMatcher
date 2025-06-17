
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., using mysqli or PDO)
// Replace these with your actual connection details
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search query from the form
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent SQL injection
// You should use proper escaping for your database system here, 
// e.g., mysqli_real_escape_string() or PDO's prepare statements.
// This is a basic example and should be improved for security.
$search_term = $conn->real_escape_string($search_term);

// SQL query for searching
$sql = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $search_term . "%'";

// Execute the query
$result = $conn->query($sql);

?>
