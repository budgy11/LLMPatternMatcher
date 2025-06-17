
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)
// and a table named 'products' with a 'name' column.

// Get the search term from the form.
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security).
$searchTerm = htmlspecialchars($searchTerm);

// Prepare the SQL query.  This is crucial for preventing SQL injection.
// Use prepared statements with placeholders.  This is the safest method.
$query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// Execute the query.
// $result = mysqli_query($conn, $query); // Assuming mysqli

// Example using PDO (more modern and often recommended):
try {
  $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE :search");
  $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  // Handle database errors.  Display an error message to the user.
  echo "Error: " . $e->getMessage();
  //  Or, for a more user-friendly experience:
  //  echo "An error occurred while searching: " . $e->getMessage();
  exit(); // Stop execution if a critical error occurs
}


// Output the search form
echo '<form method="GET" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">
      <input type="text" name="search" placeholder="Search products...">
      <button type="submit">Search</button>
    </form>';

// Display the search results.
if (count($result) > 0) {
  echo '<h2>Search Results:</h2>';
  echo '<ul>';
  foreach ($result as $row) {
    echo '<li>' . $row['name'] . ' - ' . $row['description'] . '</li>'; // Assuming 'description' column
  }
  echo '</ul>';
} else {
  echo '<p>No products found matching your search.</p>';
}

?>
