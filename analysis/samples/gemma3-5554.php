

<?php

// Assuming you have a database connection established (e.g., $db)

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term
$searchTerm = htmlspecialchars(trim($searchTerm));

// Check if the search term is empty
if (empty($searchTerm)) {
  // If empty, you can redirect to a default page or display a message
  // For example:
  // echo "Please enter a search term.";
  // header("Location: index.php"); // Redirect to your homepage
  // exit;
  $results = []; // Show empty results if nothing is searched
} else {
  // Perform the search query
  // Replace this with your actual database query
  $results = performSearch($searchTerm); 
}

// Function to perform the database search
function performSearch($searchTerm) {
  // This is a placeholder - replace with your actual query
  // Example using a hypothetical database connection:
  
  // Assuming you have a database connection $db
  
  $db = new mysqli("localhost", "username", "password", "database_name");

  if ($db->connect_error) {
      die("Connection failed: " . $db->connect_error);
  }

  $sql = "SELECT * FROM products WHERE name LIKE '%" . $db->real_escape_string($searchTerm) . "%'";  // Example using LIKE operator
  $result = $db->query($sql);

  $data = [];
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $data[] = $row;
    }
  }
  $db->close();

  return $data;
}

?>
