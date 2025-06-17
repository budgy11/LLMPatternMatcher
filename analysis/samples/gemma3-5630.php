
</body>
</html>


function performSearch($searchTerm) {
  //  This is a placeholder - replace with your actual database query
  //  Prepared statements are crucial to prevent SQL injection
  $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";  // % is a wildcard
  // You should use prepared statements here instead of string concatenation
  // Example using PDO (illustrative):
  // $stmt = $pdo->prepare($sql);
  // $stmt->execute([$searchTerm]);
  // $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Example returning dummy data for demonstration:
  $searchResults = [
    "Product 1",
    "Product 2 with Search Term",
    "Another Product with Search Term"
  ];

  return $searchResults;
}


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Replace this with your actual database connection code

// Example database connection (replace with your details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search term from the form (or URL, depending on your needs)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';  // Get from URL or set to empty if not provided
$searchTerm = htmlspecialchars($searchTerm); // Escape for security - prevents XSS

?>
