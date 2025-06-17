
</body>
</html>


<?php
// ... (previous code) ...

// Assuming you have a database connection established
$db = new mysqli("localhost", "username", "password", "database_name");

if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}

// Function to handle the search
function handleSearch($searchTerm) {
  $searchTerm = htmlspecialchars(trim($searchTerm));
  $searchTermLower = strtolower($searchTerm);

  $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTermLower . "%'";  // Use LIKE for partial matches
  $result = $db->query($sql);

  if ($result->num_rows > 0) {
    $searchResults = [];
    while($row = $result->fetch_assoc()) {
      $searchResults[] = $row['name']; // Adjust column name if needed
    }
  } else {
    $searchResults = []; // No results
  }
}

// ... (rest of the code - form, display) ...

// Close the database connection when done
$db->close();
?>
