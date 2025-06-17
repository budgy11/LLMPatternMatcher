    <input type="text" name="search_term" placeholder="Enter search term...">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Establish the database connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// ... (rest of the code from the previous example) ...

function performSearch($search_term) {
  $query = "SELECT * FROM your_table WHERE your_column LIKE '%" . $search_term . "%'"; // Use LIKE for partial matches
  $result = $conn->query($query);

  $results = [];
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $results[] = $row['your_column']; // Replace 'your_column' with the actual column name
    }
  }
  return $results;
}

// ... (Rest of the code) ...

$conn->close(); // Close the database connection
?>
