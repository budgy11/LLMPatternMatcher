

<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search query from the user (e.g., from a form submission)
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search query (important to prevent SQL injection)
$search_query = htmlspecialchars(trim($search_query));

// You'll need to replace this with your actual database query logic
// This is a basic example assuming you have a 'products' table with a 'name' column
//  that you want to search.  Adjust the query to match your database schema.

// Check if the search query is empty to avoid unnecessary queries
if (empty($search_query)) {
  $results = []; // Or display a message "No search term provided"
} else {
  $sql = "SELECT * FROM products WHERE name LIKE '%" . $search_query . "%'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    $results = [];
    while ($row = mysqli_fetch_assoc($result)) {
      $results[] = $row;
    }
  } else {
    // Handle the error - important for debugging
    echo "Error: " . mysqli_error($conn);
  }
}


?>
