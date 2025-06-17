
</body>
</html>


$stmt = mysqli_prepare($conn, "SELECT * FROM your_table WHERE your_column LIKE ?");
mysqli_stmt_bind_param($stmt, "s", $searchTerm); // "s" indicates a string parameter
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);


<?php

// Assuming you have a database connection established (e.g., $db)

// Handle the search form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = $_POST["search_term"];

  // Sanitize the search term (important to prevent SQL injection)
  $searchTerm = htmlspecialchars(trim($searchTerm)); 

  // Perform the search
  $searchResults = performSearch($searchTerm); 

  // Display the search results
  echo "<form method='post' action='search.php'>
          <input type='text' name='search_term' value='" . $searchTerm . "' placeholder='Search...'>
          <button type='submit'>Search</button>
        </form>";
  
  if (!empty($searchResults)) {
    echo "<h2>Search Results:</h2>";
    echo "<ul>";
    foreach ($searchResults as $result) {
      echo "<li>" . $result . "</li>";
    }
    echo "</ul>";
  } else {
    echo "<p>No results found for '" . $searchTerm . "'.</p>";
  }
} else {
  // Display the search form if no form has been submitted
  echo "<form method='post' action='search.php'>
          <input type='text' name='search_term' placeholder='Search...'>
          <button type='submit'>Search</button>
        </form>";
}


// Function to perform the search (replace with your actual database query)
function performSearch($searchTerm) {
  // Replace this with your database query logic.  This is just a placeholder.

  // Example:  Assume you have a 'products' table with a 'name' column
  // and you want to search for products where the name contains the search term.

  //  This is just a sample; adjust it to your database and table structure
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password'); 

  $query = "SELECT name FROM products WHERE name LIKE '%" . $db->real_escape_string($searchTerm) . "%'";
  $result = $db->query($query);

  $searchResults = array();
  if ($result) {
    while ($row = $result->fetch(PDO::FETCH_COLUMN)) {
      $searchResults[] = $row;
    }
  }

  return $searchResults;
}

?>
