
</body>
</html>


$searchTerm = $_GET['search_term'];
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";


<?php
// Database credentials (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$searchTerm = $_GET['search_term'];
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // Output data of each row
  while($row = $result->fetch_assoc()) {
    echo "Product Name: " . htmlspecialchars($row["name"]) . "<br>";
    echo "Description: " . htmlspecialchars($row["description"]) . "<br>";
    echo "<br>";
  }
} else {
  echo "No results found.";
}

$conn->close();
?>
