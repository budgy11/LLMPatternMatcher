
<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
</head>
<body>

  <h2>Search</h2>

  <form method="post" action="">
    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Simple search bar HTML form
echo '<form action="search_results.php" method="GET">';
echo '  <input type="text" name="search_term" placeholder="Search..."';
echo '      value="' . htmlspecialchars($_GET['search_term']) . '" />';
echo '  <button type="submit">Search</button>';
echo '</form>';


// Example of how to search in a database (assuming a table called 'products' with a 'name' column)
// This is just an example; adjust the query and column names to match your database
if (isset($_GET['search_term'])) {
  $searchTerm = htmlspecialchars($_GET['search_term']);

  // Escape the search term to prevent SQL injection
  $searchTermEscaped = $conn->real_escape_string($searchTerm);  // Use real_escape_string for mysqli

  //  A basic search query
  $query = "SELECT * FROM products WHERE name LIKE '%" . $searchTermEscaped . "%'";

  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    echo '<h2>Search Results for: ' . htmlspecialchars($searchTerm) . '</h2>';
    echo '<ul>';
    while ($row = $result->fetch_assoc()) {
      echo '<li>' . htmlspecialchars($row['name']) . ' - ' . htmlspecialchars($row['description']) . '</li>';  // Assuming you have a 'description' column
    }
    echo '</ul>';
  } else {
    echo '<p>No results found for: ' . htmlspecialchars($searchTerm) . '</p>';
  }
}

?>
