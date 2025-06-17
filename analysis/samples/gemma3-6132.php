
</body>
</html>


<?php

// Assuming you have a database connection established
// (e.g., using mysqli or PDO) -  Replace this placeholder with your actual connection

// Example using mysqli:
// $conn = new mysqli("localhost", "username", "password", "database_name");
// if ($conn->connect_error) {
//   die("Connection failed: " . $conn->connect_error);
// }

// Placeholder connection - Remove this and use your actual connection
$conn = null; // Initialize to null

// Search query
$searchTerm = isset($_GET['search']) ? $_GET['search'] : ''; // Get search term from URL

// Sanitize the search term (IMPORTANT for security)
$searchTerm = htmlspecialchars($searchTerm);

// Build the SQL query
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";  //  Adjust 'products' table name and 'name' column

// Execute the query
$result = $conn->query($sql);

?>
