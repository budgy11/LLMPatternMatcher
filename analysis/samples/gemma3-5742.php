
</body>
</html>


// Example with prepared statements (much more secure)
$stmt = $db->prepare("SELECT * FROM products WHERE title LIKE ? OR description LIKE ?");
$stmt->bind_param("s", "%" . $search_term . "%", "%" . $search_term . "%"); // String types
$stmt->execute();
$result = $stmt->get_result();

// ... (Output the results as before)


<?php

// Assuming you have a database connection established (e.g., mysqli)
// and you want to search through a table named 'products'
// with a column named 'name'

// Example database connection (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important to prevent SQL injection)
$searchTerm = $conn->real_escape_string($searchTerm);


// Perform the search
$query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
$result = $conn->query($query);

?>
