
</body>
</html>


<?php

// Assuming you have a database connection established, for example:
// $db = new mysqli("localhost", "username", "password", "database_name");

// Check if the connection is successful
// if ($db->connect_error) {
//   die("Connection failed: " . $db->connect_error);
// }

// --- Search Bar Implementation ---

// 1. Get the search term from the request
$searchTerm = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';  // Use htmlspecialchars for security

// 2. Sanitize and Validate the Search Term (IMPORTANT!)
//    -  Remove whitespace, special characters, and possibly limit the length
$searchTerm = trim($searchTerm); // Remove leading/trailing whitespace
$searchTerm = preg_replace('/[^a-zA-Z0-9\s]/', '', $searchTerm); //Allow only letters, numbers, and spaces. Adjust as needed
$searchTerm = $searchTerm; // Keep trimmed value. You can further limit the length if needed.

// 3.  Query the Database
//    -  Replace this with your actual database query
//    -  This is a simplified example using a hypothetical 'products' table
$sql = "SELECT id, name, description, price FROM products WHERE name LIKE '%" . $searchTerm . "%' OR description LIKE '%" . $searchTerm . "%'";

// 4. Execute the query
$result = $db->query($sql);

// 5. Display the results
if ($result->num_rows > 0) {
  echo "<ul>";
  while ($row = $result->fetch_assoc()) {
    echo "<li>" . $row['name'] . " - " . $row['description'] . " - " . $row['price'] . "</li>";
  }
  echo "</ul>";
} else {
  echo "No results found for '" . $searchTerm . "'.";
}

// 6. Close the database connection (important!)
// $db->close();

?>
