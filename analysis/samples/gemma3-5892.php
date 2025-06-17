

<?php

// Sample data (replace with your actual data source - e.g., database)
$items = [
  "apple",
  "banana",
  "cherry",
  "date",
  "elderberry",
  "fig",
  "grape",
  "honeydew",
  "kiwi",
  "lemon",
  "mango",
  "nectarine",
  "orange",
  "pear",
  "quince",
  "raspberry",
  "strawberry",
  "tangerine",
  "watermelon"
];

// Get the search term from the form submission
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars($searchTerm);

// Perform the search
$searchResults = [];
foreach ($items as $item) {
  if (stripos($item, $searchTerm) !== false) { // Case-insensitive search
    $searchResults[] = $item;
  }
}

?>
