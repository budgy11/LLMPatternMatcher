
</body>
</html>


function searchDatabase($searchTerm) {
  // Replace with your actual database connection code
  // Assuming you have a database connection called $db

  $db = new mysqli("localhost", "username", "password", "database_name");

  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }

  // Example query - adjust based on your table structure
  $query = "SELECT * FROM products WHERE name LIKE '%" . $db->real_escape_string($searchTerm) . "%'"; // Use real_escape_string to prevent SQL injection
  // IMPORTANT: Use $db->real_escape_string() to sanitize the input to prevent SQL injection attacks.

  $result = $db->query($query);

  if ($result->num_rows > 0) {
    $results = [];
    while ($row = $result->fetch_assoc()) {
      $results[] = $row['name']; // Adjust based on your product's name column
    }
  }

  $result->close();
  return $results;
}


<?php

// Simulate a database or data source (replace with your actual data source)
$products = [
    "Laptop X1",
    "Gaming Mouse Y2",
    "Wireless Keyboard Z3",
    "Monitor ABC",
    "SSD 1TB",
    "Laptop X1 Pro",
    "Gaming Mouse Y2 Plus"
];

// Get the search query from the form (using GET method - you can change this to POST)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent XSS vulnerabilities
$searchTerm = htmlspecialchars($searchTerm); // Always sanitize user input!

// Perform the search
$searchResults = [];
foreach ($products as $product) {
    if (stripos($product, $searchTerm) !== false) { // Case-insensitive search
        $searchResults[] = $product;
    }
}

?>
