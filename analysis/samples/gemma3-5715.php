
</body>
</html>


// (Assuming you've established $db and $stmt)
$searchTerm = htmlspecialchars($_GET['search']);

// Build the prepared statement
$sql = "SELECT * FROM $tableName WHERE ";

$columns = ['name', 'description'];
$conditions = [];

foreach ($columns as $column) {
  $conditions[] = "$column LIKE ?";
}

$sql .= implode(' OR ', $conditions);

// Prepare the statement
$stmt = $db->prepare($sql);
if ($stmt === false) {
    // Handle the error
    echo "Error preparing statement: " . $db->error;
    exit;
}

// Bind parameters
foreach ($columns as $column) {
  $stmt->bind_param($column, "%$searchTerm%"); // Use % to escape the variable
}

// Execute the query
$stmt->execute();

// Get results
$results = $stmt->get_result();

// Close statement
$stmt->close();


<?php

// Sample data (replace with your actual data source - e.g., database)
$data = [
  "apple", "banana", "cherry", "date", "elderberry",
  "fig", "grape", "honeydew", "kiwi", "lemon"
];

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars($searchTerm);  // Prevents XSS

// Perform the search
$searchResults = [];
if (!empty($searchTerm)) {
  foreach ($data as $item) {
    if (stripos($item, $searchTerm) !== false) {  // Case-insensitive search
      $searchResults[] = $item;
      break; // Stop searching after finding the first match
    }
  }
}

?>
