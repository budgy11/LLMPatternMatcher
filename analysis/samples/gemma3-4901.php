

<?php

// Assuming you have a database connection established (e.g., mysqli)
// Replace these with your actual database connection details
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get the search term from the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = trim($_POST["search_term"]);

  // Sanitize the search term to prevent SQL injection
  $searchTerm = $conn->real_escape_string($searchTerm);

  // Perform the search (replace with your actual query)
  $query = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $searchTerm . "%'";

  // Execute the query
  $result = $conn->query($query);

  // Display the results
  if ($result->num_rows > 0) {
    echo "<table border='1'>";
    while($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . $row["your_column_name"] . "</td>"; // Replace with your column names
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "No results found for '" . $searchTerm . "'.";
  }
}

?>
