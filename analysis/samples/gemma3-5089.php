        <button type="submit">Search</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db connection)
// and a table named 'products' with a column named 'name' for search.

// Check if the search form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Sanitize the search term to prevent SQL injection
  $searchTerm = $db->real_escape_string($searchTerm);  // Use your database library's escape function

  // Perform the search
  $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'"; // Using LIKE for partial matches
  $result = $db->query($sql);

  // Display the results
  if ($result->num_rows > 0) {
    echo "<form method='post' action=''>";
    echo "<input type='text' name='search_term' value='" . $searchTerm . "' placeholder='Enter search term'>";
    echo "<button type='submit'>Search</button>";
    echo "</form>";

    echo "<h2>Search Results:</h2>";
    while ($row = $result->fetch_assoc()) {
      echo "<p>" . $row["name"] . " - " . $row["description"] . "</p>";  // Display relevant product fields
    }
  } else {
    echo "<p>No results found for '" . $searchTerm . "'.</p>";
  }

} else {
  // Display the search form if no form has been submitted
  echo "<form method='post' action=''>";
  echo "<input type='text' name='search_term' placeholder='Enter search term'>";
  echo "<button type='submit'>Search</button>";
  echo "</form>";
}

?>
