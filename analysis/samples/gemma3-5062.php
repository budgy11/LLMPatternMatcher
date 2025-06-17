
</body>
</html>


<?php

// Sample data (replace with your actual data source)
$data = [
    "apple", "banana", "cherry", "date", "elderberry", "fig", "grape"
];

// Function to handle the search
function searchData($searchTerm, $data) {
  // Sanitize the search term (important for security)
  $searchTerm = htmlspecialchars(trim($searchTerm));

  // Convert to lowercase for case-insensitive search
  $searchTerm = strtolower($searchTerm);

  $results = [];
  foreach ($data as $item) {
    $itemLower = strtolower($item);
    if (strpos($itemLower, $searchTerm) !== false) {  // strpos returns position or false if not found
      $results[] = $item;
      break; // Stop after the first match (optional - can remove for multiple results)
    }
  }

  return $results;
}

// Get the search term from the form (or URL if needed)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Perform the search
$searchResults = searchData($searchTerm, $data);

?>
