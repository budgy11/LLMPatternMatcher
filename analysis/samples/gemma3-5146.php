

<?php
// Example using prepared statements to prevent SQL injection

function searchData($searchTerm) {
  global $db;

  // Construct the prepared statement
  $stmt = $db->prepare("SELECT * FROM my_table WHERE title LIKE ?");
  $searchTermEscaped = $db->real_escape_string($searchTerm); // Escape the search term

  // Bind the parameters
  $stmt->bind_param("s", $searchTermEscaped);

  // Execute the statement
  $stmt->execute();

  // Get the results
  $results = array();
  while ($row = $stmt->fetch_assoc()) {
    $results[] = $row['title'];
  }

  // Close the statement
  $stmt->close();

  return $results;
}
?>
