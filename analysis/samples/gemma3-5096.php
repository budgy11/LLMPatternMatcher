
</body>
</html>


<?php
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

$search_term = htmlspecialchars(trim($search_term));

// Establish the database connection (using mysqli)
$mysqli = new mysqli("localhost", "username", "password", "database_name");

if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
}

// SQL query construction
$sql = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $search_term . "%'";

// Execute the query
$result = $mysqli->query($sql);

// Close the connection
$mysqli->close();
?>
