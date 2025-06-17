
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., using mysqli)
// and you've defined $db as your connection object.
// Replace this with your actual database connection setup.

// Example Database Connection (replace with your actual credentials)
// $db = new mysqli("localhost", "username", "password", "database_name");
// if($db->connect_error){
//   die("Connection failed: " . $db->connect_error);
// }

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize and prepare the search term (important for security)
$searchTerm = htmlspecialchars($searchTerm); // Escape HTML special characters
// Ideally, you'd use prepared statements with parameterized queries
// to prevent SQL injection.  This example just demonstrates basic escaping.

// Build the SQL query
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'"; // Adjust 'products' and 'name' to your table and column names

// Execute the query
$result = $db->query($sql);

?>
