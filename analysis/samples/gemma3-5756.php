

<?php

// Assuming you have a database connection established (e.g., $db)
// and you want to search through a table named 'products' with a 'name' column.

// Handle the search form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = isset($_POST["search_term"]) ? trim($_POST["search_term"]) : "";

  // Sanitize the search term to prevent XSS vulnerabilities
  $searchTerm = htmlspecialchars($searchTerm);

  // Perform the search
  if ($searchTerm) {
    $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";  // Use LIKE for partial matches
    $result = mysqli_query($db, $sql);

    if ($result) {
      echo "<form method='post' action='search_results.php'>
              <input type='text' name='search_term' value='" . $searchTerm . "' placeholder='Search products...'>
              <button type='submit'>Search</button>
            </form>";

      echo "<br>";

      echo "<h2>Search Results for: " . $searchTerm . "</h2>";

      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<p>" . $row["name"] . " - " . $row["description"] . "</p>";
        }
      } else {
        echo "<p>No products found matching your search criteria.</p>";
      }

      mysqli_free_result($result);
    } else {
      echo "<p>Error executing query: " . mysqli_error($db) . "</p>";
    }
  }
?>
