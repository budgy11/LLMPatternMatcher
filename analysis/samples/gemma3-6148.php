    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., using mysqli)
//  $db = new mysqli("localhost", "username", "password", "database_name");
//  if (!$db) {
//    die("Connection failed!");
//  }

// Initialize a variable to hold the search query
$search_query = "";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize the input to prevent SQL injection
  $search_query = $db->real_escape_string($_POST["search_term"]);

  // Perform the search
  $query = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $search_query . "%'";
  $result = $db->query($query);

  // Display the results
  if ($result->num_rows > 0) {
    echo "<h2>Search Results:</h2>";
    while ($row = $result->fetch_assoc()) {
      echo "<div>";
      // Display the data from your table here.  Example:
      echo "<p>Name: " . htmlspecialchars($row["name"]) . "</p>";
      echo "<p>Description: " . htmlspecialchars($row["description"]) . "</p>";
      echo "</div>";
    }
  } else {
    echo "<p>No results found.</p>";
  }
}
?>
