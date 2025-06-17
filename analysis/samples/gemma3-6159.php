    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your credentials)
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

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = $_POST["search_term"];

  // Sanitize the search term (CRUCIAL - use prepared statements instead)
  $searchTerm = $conn->real_escape_string($searchTerm); //minimal sanitization, for demonstration

  // Build the SQL query (USE PREPARED STATEMENTS FOR REAL APPLICATIONS)
  $sql = "SELECT * FROM your_table WHERE name LIKE '%" . $searchTerm . "%'";

  // Execute the query (USE PREPARED STATEMENTS)
  $result = $conn->query($sql);

  // Display the results
  echo "<h2>Search Results for: " . $searchTerm . "</h2>";
  if ($result->num_rows > 0) {
    echo "<ul>";
    while($row = $result->fetch_assoc()) {
      echo "<li>ID: " . $row["id"] . ", Name: " . $row["name"] . "</li>";
    }
    echo "</ul>";
  } else {
    echo "<p>No results found.</p>";
  }

  $conn->close();
}
?>
