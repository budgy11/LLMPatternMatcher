

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Let's call the database connection $conn

// Example database setup (replace with your actual connection)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get the search term from the form input
$searchTerm = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

//  Handle the search submission
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  // Perform the search
  $sql = "SELECT * FROM your_table WHERE your_column LIKE '%" . $searchTerm . "%'"; // Replace your_table and your_column
  $result = $conn->query($sql);

  // Display the results
  if ($result->num_rows > 0) {
    echo "<h2>Search Results for: " . htmlspecialchars($searchTerm) . "</h2>";
    echo "<form method='get' action=''>";
    echo "<input type='text' name='search' placeholder='Enter search term'>";
    echo "<button type='submit'>Search</button>";
    echo "</form>";

    echo "<ul>";
    while($row = $result->fetch_assoc()) {
      echo "<li>" . $row["your_column"] . " - " . $row["your_other_column"] . "</li>"; // Replace with your actual column names
    }
    echo "</ul>";

  } else {
    echo "<p>No results found for '" . htmlspecialchars($searchTerm) . "'</p>";
  }

} else {
  // If the request method is not GET (e.g., a POST request)
  // Display the search form
  echo "<h2>Search</h2>";
  echo "<form method='get' action=''>";
  echo "<input type='text' name='search' placeholder='Enter search term'>";
  echo "<button type='submit'>Search</button>";
  echo "</form>";
}

// Close the database connection
$conn->close();

?>
