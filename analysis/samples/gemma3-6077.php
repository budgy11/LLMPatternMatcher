

<?php
// Database credentials (replace with your actual values)
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die('Connection failed: ' . $conn->connect_error);
}

// Get the search term
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Construct the SQL query
$query = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'";

// Execute the query
$result = $conn->query($query);

// Display the search form and results
echo '<form action="" method="get">';
echo 'Search: <input type="text" name="search" value="' . htmlspecialchars($search_term) . '" />';
echo '<input type="submit" value="Search">';
echo '</form>';

if ($result->num_rows > 0) {
  echo '<h2>Search Results:</h2>';
  echo '<ul>';
  while ($row = $result->fetch_assoc()) {
    echo '<li>' . $row['name'] . ' - ' . $row['description'] . '</li>';
  }
  echo '</ul>';
} else {
  echo '<p>No products found for "' . htmlspecialchars($search_term) . '"</p>';
}

$conn->close(); // Close the database connection
?>
