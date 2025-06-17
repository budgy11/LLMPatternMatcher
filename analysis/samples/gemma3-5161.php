    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// and you have a table named 'products' with a column 'name'

// 1. Handle the Search Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = trim($_POST["search_term"]);

  // Sanitize the search term to prevent SQL injection
  $searchTerm = mysqli_real_escape_string($conn, $searchTerm);

  // Validate the search term (optional, but recommended)
  if (empty($searchTerm)) {
    // Do nothing or display an error message (e.g., "Please enter a search term")
    echo "<p>Please enter a search term.</p>";
  } else {
    // 2.  Construct the SQL Query
    $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

    // 3. Execute the Query
    $result = mysqli_query($conn, $sql);

    // 4. Display the Results
    if (mysqli_num_rows($result) > 0) {
      echo "<h2>Search Results for: " . htmlspecialchars($searchTerm) . "</h2>";
      echo "<table border='1'>";
      echo "<tr><th>ID</th><th>Name</th><th>Description</th></tr>"; // Example columns
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>"; // Assuming 'id' is a column in your table
        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["description"]) . "</td>"; // Adjust based on your table structure
        echo "</tr>";
      }
      echo "</table>";
    } else {
      echo "<p>No results found for: " . htmlspecialchars($searchTerm) . "</p>";
    }
  }
}

?>
