
</body>
</html>


<?php
// ... (Database connection code - same as before) ...

$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

// Sanitize the search term (optional, but good practice)
$search_term = $conn->real_escape_string($search_term);

// Prepare the statement
$stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ?");

if ($stmt) {
  // Bind the parameter
  $stmt->bind_param("s", $search_term); // "s" indicates a string parameter

  // Execute the statement
  $stmt->execute();

  // Get the result set
  $result = $stmt->get_result();

  // Free the statement
  $stmt->close();
} else {
  // Handle the error if the statement preparation fails
  echo "Error preparing statement.";
}

// ... (Result display code - same as before) ...
?>
