    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// ... (Database connection setup - same as before)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = $_POST["search_term"];

  // Sanitize the search term (still recommended for other reasons)
  $searchTerm = htmlspecialchars($searchTerm);

  // Use a prepared statement to prevent SQL injection
  $sql = "SELECT * FROM products WHERE name LIKE ?"; // Use a placeholder '?'

  // Example using mysqli
  $conn = new mysqli("your_db_host", "your_db_user", "your_db_password", "your_db_name");

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $searchTerm); // "s" indicates a string parameter

  $result = $stmt->execute();

  if ($result) {
    $rows = $stmt->fetch_all(MYSQLI_ASSOC); // Fetch all results as an array
    if (!empty($rows)) {
      echo "<h2>Search Results for: " . $searchTerm . "</h2>";
      echo "<table border='1'>";
      echo "<tr><th>ID</th><th>Name</th><th>Description</th></tr>";
      foreach ($rows as $row) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["description"] . "</td>";
        echo "</tr>";
      }
      echo "</table>";
    } else {
      echo "<p>No results found for: " . $searchTerm . "</p>";
    }
  } else {
    echo "<p>Error executing query: " . $conn->error . "</p>";
  }

  $stmt->close();
  $conn->close();
}
?>
