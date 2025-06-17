
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)

// Function to search the database
function searchDatabase($searchTerm) {
  global $db;  // Access the database connection

  $query = "SELECT * FROM products WHERE name LIKE '%" . $db->real_escape_string($searchTerm) . "%'";  // Use real_escape_string for database safety
  $result = $db->query($query);

  $results = array();
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $results[] = $row['name'] . " - " . $row['description']; // Customize the output
    }
  }

  return $results;
}

// (The rest of the HTML form and PHP code from above goes here)
?>
