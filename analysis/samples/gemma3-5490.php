

$searchTerm = htmlspecialchars($_GET['search']);
$stmt = $db->prepare("SELECT * FROM products WHERE name LIKE ?");
$stmt->bind_param("s", $searchTerm); // "s" indicates a string parameter
$stmt->execute();
$result = $stmt->get_result();

// ... (process the result)


<?php

// Assuming you have a database connection established (e.g., $db)

// Get the search term from the form (if present)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars(trim($searchTerm));

// Check if the search term is not empty
if (!empty($searchTerm)) {

  // --- Example: Search in a table named 'products' with a 'name' column ---
  $query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
  $result = mysqli_query($db, $query);

  if ($result) {
    echo "<form method='get' action='search_results.php'>
          <input type='text' name='search' value='" . $searchTerm . "' placeholder='Search products...'>
          <button type='submit'>Search</button>
        </form>";

    echo "<br>";

    // Display search results
    if (mysqli_num_rows($result) > 0) {
      echo "<h3>Search Results for: " . $searchTerm . "</h3>";
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<div>";
        echo "<strong>" . $row['name'] . "</strong> - " . $row['description'] . "<br>";
        echo "<a href='product_details.php?id=" . $row['id'] . "'>View Details</a><br>";
        echo "</div>";
      }
    } else {
      echo "<p>No products found matching your search.</p>";
    }

    mysqli_free_result($result); // Free the result set
  } else {
    echo "<p>Error executing the query.</p>";
    echo mysqli_error($db); // Display the error for debugging
  }

} else {
  // Display the search form if no search term is provided.
  echo "<form method='get' action='search_results.php'>
        <input type='text' name='search' value='" . $searchTerm . "' placeholder='Search products...'>
        <button type='submit'>Search</button>
      </form>";
}

?>
