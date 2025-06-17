
</body>
</html>


<?php
// Database credentials (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// ... (rest of the code, including the form handling and search logic)

// Example database query (replace with your actual query)
$sql = "SELECT * FROM fruits"; // Assuming you have a table named "fruits"
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // Process the results
  $data = [];
  while ($row = $result->fetch_assoc()) {
    $data[] = $row['name']; // Assuming a 'name' column
  }
} else {
  $data = [];
}

// ... (close the database connection when finished)
$conn->close();

?>
