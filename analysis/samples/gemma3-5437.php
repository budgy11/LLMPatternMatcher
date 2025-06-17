
</body>
</html>


<?php
// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
  $searchTerm = htmlspecialchars($_GET['search']);

  // Example query (adjust to your table and column names)
  $sql = "SELECT * FROM items WHERE name LIKE '%" . $searchTerm . "%'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    echo "<h2>Search Results:</h2>";
    echo "<ul>";
    while($row = $result->fetch_assoc()) {
      echo "<li>" . $row["name"] . "</li>"; // Assuming 'name' is your column name
    }
    echo "</ul>";
  } else {
    echo "<p>No results found for '" . $searchTerm . "'.</p>";
  }
} else {
  echo "<p>Enter your search term above.</p>";
}

$conn->close();
?>
