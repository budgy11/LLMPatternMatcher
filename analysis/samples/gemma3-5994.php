

<?php

// Assuming you have a database connection established (e.g., $db)
// and a table named 'products' with a 'name' column

// Function to handle the search query
function searchProducts($query) {
  global $db; // Assuming $db is your database connection object

  // Sanitize and escape the query to prevent SQL injection
  $query = $db->real_escape_string($query);

  // Build the SQL query
  $sql = "SELECT * FROM products WHERE name LIKE '%" . $query . "%'";

  // Execute the query
  $result = $db->query($sql);

  // Check if the query was successful
  if ($result) {
    // Build the output HTML
    echo '<form method="GET" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">';
    echo '<input type="text" name="search" value="' . htmlspecialchars($query) . '" placeholder="Search Products">';
    echo '<button type="submit">Search</button>';
    echo '</form>';

    // Display the search results
    echo '<ul>';
    while ($row = $result->fetch_assoc()) {
      echo '<li>' . htmlspecialchars($row['name']) . ' - ' . htmlspecialchars($row['description']) . '</li>'; // Adjust 'description' based on your table
    }
    echo '</ul>';
  } else {
    echo '<p>No products found matching your search.</p>';
  }
}


// Example usage:
// Call the function to handle the search
searchProducts($_GET['search']);
?>
