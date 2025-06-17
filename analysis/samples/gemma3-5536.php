
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Let's assume you have a variable $db is your database connection object

// Example database setup (replace with your actual details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$db = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}


// Function to handle the search query
function searchData($db, $searchTerm) {
  // Sanitize the search term to prevent SQL injection
  $searchTerm = $db->real_escape_string($searchTerm);

  // Build the SQL query
  $sql = "SELECT * FROM your_table_name WHERE column_name LIKE '%" . $searchTerm . "%'";

  // Execute the query
  $result = $db->query($sql);

  // Prepare the results
  $results = array();
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $results[] = $row;
    }
  }

  return $results;
}

?>
