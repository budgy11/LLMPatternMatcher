
</body>
</html>


<?php
// ... (HTML form as above) ...

// Assuming you have a database connection established
// (e.g., $conn = mysqli_connect("localhost", "username", "password", "database");)

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$searchTerm = htmlspecialchars(trim($searchTerm));

// SQL query (always sanitize your inputs to prevent SQL injection!)
$query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = mysqli_query($conn, $query);

$searchResults = [];
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $searchResults[] = htmlspecialchars($row['name']); // Assuming 'name' is the column
  }
  mysqli_free_result($result); // Free up the result set
}

// ... (rest of the HTML output) ...
?>
