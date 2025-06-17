

<?php

// Assuming you have a database connection established and available in a variable named $db

// Example database connection (replace with your actual connection details)
// $db = new mysqli("localhost", "username", "password", "database_name");
// if ($db->connect_error) {
//   die("Connection failed: " . $db->connect_error);
// }

// Get the search term from the form (using $_GET or $_POST)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent SQL injection
$searchTerm = $db->real_escape_string($searchTerm);

// --- Search Logic ---
// You can customize this section to implement your desired search logic.

// 1. Simple Keyword Search (in a single table)
if (empty($searchTerm)) {
  // Display a message or redirect if no search term is provided
  echo "<p>Enter a search term.</p>";
} else {
  // Assuming you have a table named 'products' with a 'name' or 'description' column
  $query = "SELECT * FROM products WHERE name LIKE '%$searchTerm%' OR description LIKE '%$searchTerm%'";

  $result = $db->query($query);

  if ($result->num_rows > 0) {
    echo "<form method='get' action='results.php'> <!--  'results.php' is a suggested page to display results -->
          <input type='text' name='search' value='$searchTerm'>
          <input type='submit' value='Search'>
        </form>";

    echo "<p>Search Results for: <b>$searchTerm</b></p>";

    // Display the search results (iterate through the result set)
    while ($row = $result->fetch_assoc()) {
      echo "<p>Product Name: " . htmlspecialchars($row['name']) . ", Description: " . htmlspecialchars($row['description']) . "</p>";
    }

  } else {
    echo "<p>No results found for <b>$searchTerm</b>.</p>";
  }
}

// --- Example for searching across multiple tables (more complex) ---
// This is just a placeholder - adapt it to your database schema
/*
$query = "SELECT p.name, c.category_name
          FROM products p
          JOIN categories c ON p.category_id = c.id
          WHERE p.name LIKE '%$searchTerm%' OR c.category_name LIKE '%$searchTerm%'";
*/

?>
