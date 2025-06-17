
<!DOCTYPE html>
<html>
<head>
  <title>Search Example</title>
</head>
<body>

  <h1>Search Products</h1>

  <form method="post" action="search.php">
    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., using mysqli)
// Let's assume you have a $db connection object

// Function to handle the search form submission
function handleSearch($searchTerm) {
  // Sanitize the search term to prevent SQL injection
  $searchTerm = htmlspecialchars($searchTerm);

  // Example: Search in a table called 'products' with a column called 'name'
  // Replace 'products' and 'name' with your actual table and column names.
  $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

  // Execute the query
  $result = mysqli_query($db, $sql);

  // Check if any results were found
  if (mysqli_num_rows($result) > 0) {
    // Output the results
    echo "<h2>Search Results for: " . $searchTerm . "</h2>";
    echo "<table border='1'>";
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr>";
      echo "<td>" . $row['name'] . "</td>"; // Assuming 'name' is the column you want to display
      // Add other columns as needed
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "<p>No results found for: " . $searchTerm . "</p>";
  }
}


// Example usage:

// HTML form for the search bar
echo "<form method='GET' action='search.php'>";
echo "Search: <input type='text' name='search_term' style='width: 200px;'>";
echo "<button type='submit'>Search</button>";
echo "</form>";

// Call the function to handle the search submission
// This part is executed when the form is submitted (GET method)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  // Get the search term from the form
  $searchTerm = isset($_GET["search_term"]) ? $_GET["search_term"] : "";

  // Handle the search
  handleSearch($searchTerm);
}

?>
