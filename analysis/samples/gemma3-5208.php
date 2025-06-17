

<?php

// Assuming you have a database connection established (e.g., $db)

// Function to handle the search query
function performSearch($searchTerm, $db) {
  // Sanitize the search term to prevent SQL injection
  $searchTerm = mysqli_real_escape_string($db, $searchTerm);

  // Example: Search in a table named 'products' with a column 'name'
  $query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

  $result = mysqli_query($db, $query);

  if (mysqli_num_rows($result) > 0) {
    // Display the results
    echo "<form method='get' action='search_results.php'>
          <input type='hidden' name='search_term' value='" . $searchTerm . "'>
          <input type='text' name='search_term' placeholder='Search...'>
          <button type='submit'>Search</button>
        </form>";

    echo "<br>";

    while ($row = mysqli_fetch_assoc($result)) {
      echo "Product Name: " . $row['name'] . "<br>";
      echo "Description: " . $row['description'] . "<br>";
      // Add other product fields as needed
      echo "<hr>";
    }
  } else {
    echo "<p>No results found for '" . $searchTerm . "'</p>";
  }
}

// Example Usage (assuming a form element with id 'search_input')
if (isset($_POST['search_term'])) {
  $searchTerm = $_POST['search_term'];
  performSearch($searchTerm, $db);
}

// If it's a GET request (e.g., user typed something directly into the URL)
if (isset($_GET['search_term'])) {
  $searchTerm = $_GET['search_term'];
  performSearch($searchTerm, $db);
}

// If the search form is being submitted
if (isset($_POST['search_term'])) {
    $searchTerm = $_POST['search_term'];
    // You can also handle the search here - for example, save the search term
    // to a session or database.
}
?>
