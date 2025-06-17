
<!-- HTML Form to submit the search -->
<form method="get" action="">
    <input type="text" name="search" placeholder="Search...">
    <button type="submit">Search</button>
</form>


<?php

// Assuming you have a database connection established (e.g., $db connection)

// Get the search term from the input field
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars(trim($searchTerm));

//  Example:  Search in a table called 'products' with a column named 'name'

//Option 1: Simple LIKE operator (case-insensitive)
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// Option 2: Case-sensitive search
// $sql = "SELECT * FROM products WHERE name = '" . $searchTerm . "'";


// Execute the query
$result = mysqli_query($db, $sql);

// Check for errors
if ($result) {
  // Display the search results
  echo "<form method='get' action=''>";
  echo "<input type='text' name='search' value='" . $searchTerm . "' placeholder='Search'>";
  echo "<button type='submit'>Search</button>";
  echo "</form>";

  echo "<h2>Search Results:</h2>";

  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<p>" . $row['name'] . "</p>"; // Assuming 'name' is the column you want to display
      // You can display other columns from the row here as needed.
    }
  } else {
    echo "<p>No results found.</p>";
  }
} else {
  echo "<p>Error executing query: " . mysqli_error($db) . "</p>";
}

// Close the database connection (important!)
mysqli_close($db);
?>
