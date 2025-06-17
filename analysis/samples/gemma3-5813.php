

<?php

// Assuming you have a database connection established and named $conn

if (isset($_SERVER["REQUEST_METHOD"]) && "POST" == $_SERVER["REQUEST_METHOD"]) {
  // Get the search term from the form data
  $searchTerm = htmlspecialchars(trim($_POST["search_term"])); // Escape for security, trim whitespace

  // Validation -  Important!  Don't just blindly use user input.
  if (empty($searchTerm)) {
    // Handle empty search term (e.g., display a message)
    $results = [];
    echo "<p>Please enter a search term.</p>";
    return;
  }

  // **Database Query (Replace with your actual database setup)**
  $query = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $searchTerm . "%'";
  $result = mysqli_query($conn, $query); // Or your database library's query function

  if (mysqli_num_rows($result) > 0) {
    // Display the results
    echo "<h2>Search Results for: " . htmlspecialchars($searchTerm) . "</h2>";
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<li>" . htmlspecialchars($row['your_column_name']) . "</li>"; // Adapt to your table structure
    }
    echo "</ul>";
  } else {
    // Handle no results found
    echo "<p>No results found for: " . htmlspecialchars($searchTerm) . "</p>";
  }

  // Close the database connection (important!)
  mysqli_close($conn);
}
?>
