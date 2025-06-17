
</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

try {
  $conn = new mysqli($host, $username, $password, $database);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
} catch (Exception $e) {
  die("Connection failed: " . $e->getMessage());
}


function search_results_db($search_term) {
  $search_term = htmlspecialchars(trim($search_term));

  $query = "SELECT name, description FROM products WHERE name LIKE '%" . $search_term . "%'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $results = [];
    while ($row = $result->fetch_assoc()) {
      $results[] = [
        "name" => $row["name"],
        "description" => $row["description"],
      ];
    }
    return $results;
  } else {
    return [];
  }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  $results = search_results_db($search_term);
}

// Close the database connection (important!)
$conn->close();

// ... (rest of the HTML code remains the same)
?>
