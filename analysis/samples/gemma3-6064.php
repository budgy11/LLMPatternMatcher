

<?php

// Assuming you have a database connection established (e.g., $conn)
// This is a basic example, you'll need to adapt it to your database structure and query.

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security - prevents SQL injection)
$searchTerm = htmlspecialchars($searchTerm);

// Check if the search term is empty
if (empty($searchTerm)) {
  // Display a message or show results for all items if you prefer
  echo "Please enter a search term.";
  // Or, if you want to display all items without filtering
  // $results = get_all_items(); // Replace with your function
  // echo $results;
} else {
  // Build the SQL query (adapt this to your database structure)
  $sql = "SELECT * FROM items WHERE name LIKE '%" . $searchTerm . "%'"; 

  // Execute the query
  $result = mysqli_query($conn, $sql);  // Replace $conn with your actual database connection

  if ($result) {
    echo "<form method='get' action='search_results.php'>
          <input type='text' name='search' value='" . $searchTerm . "'><button type='submit'>Search</button>
          </form>";

    echo "<h2>Search Results for: " . $searchTerm . "</h2>";

    // Display the search results
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<p>" . $row['name'] . " - " . $row['description'] . "</p>"; // Adapt to your column names
    }

    // Close the database connection (important!)
    mysqli_free_result($result);
    mysqli_close($conn);
  } else {
    echo "<p>No results found for: " . $searchTerm . "</p>";
  }
}
?>
