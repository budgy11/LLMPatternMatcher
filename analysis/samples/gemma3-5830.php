  <input type="text" name="search_term" placeholder="Enter search term">
  <input type="submit" value="Search">
</form>


<?php
// Assume $db_connection is your database connection

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
  $searchTerm = isset($_POST['search_term']) ? trim($_POST['search_term']) : '';

  if (empty($searchTerm)) {
    echo "<p>Please enter a search term.</p>";
    exit;
  }

  // Prepared statement (using mysqli):
  $query = "SELECT * FROM your_table WHERE your_column LIKE ?";
  $searchTermEscaped = $db_connection->real_escape_string($searchTerm); // still necessary, but less risky

  $stmt = $db_connection->prepare($query);
  if ($stmt) {
    $stmt->bind_param("s", $searchTermEscaped); // "s" indicates a string parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      echo "<ul>";
      while ($row = $result->fetch_assoc()) {
        echo "<li>" . $row['your_column'] . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found for '" . $searchTerm . "'.</p>";
    }
    $stmt->close();
  } else {
    echo "<p>Error preparing statement.</p>";
    // Handle the error (e.g., log it)
  }
}
?>
