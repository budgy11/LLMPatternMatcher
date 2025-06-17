
</body>
</html>


<?php
// Assuming you have a database connection established in a variable called $conn

if (isset($_GET["search_term"]) && !empty($_GET["search_term"])) {
  $search_term = $_GET["search_term"];

  // Sanitize the search term (important, but doesn't replace prepared statements)
  $search_term = $mysqli->real_escape_string($search_term);

  $sql = "SELECT * FROM my_table WHERE title LIKE '%" . $search_term . "%'";  // Use LIKE for partial matches
  $result = $mysqli->query($sql);

  if ($result) {
    // Process the results
    while ($row = $result->fetch_assoc()) {
      echo "<p>" . htmlspecialchars($row["title"]) . "</p>";
    }
    $result->free();
  } else {
    echo "<p>Error querying the database.</p>";
  }
}
?>
