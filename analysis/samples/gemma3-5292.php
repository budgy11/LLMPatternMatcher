

<?php

// Sample data - replace with your actual data source
$data = [
    "apple",
    "banana",
    "orange",
    "grape",
    "pineapple",
    "strawberry",
    "watermelon",
    "kiwi",
];

// Function to handle the search
function searchData($searchTerm, $data) {
  $results = [];

  // Convert the search term to lowercase for case-insensitive searching
  $searchTerm = strtolower($searchTerm);

  foreach ($data as $item) {
    // Convert the item to lowercase for case-insensitive searching
    $itemLower = strtolower($item);

    if (strpos($itemLower, $searchTerm) === 0) {  // Starts with the search term
      $results[] = $item;
      break; // Stop after finding the first match to improve performance
    }
  }

  return $results;
}

// Get the search term from the form (if submitted)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Perform the search
$searchResults = searchData($searchTerm, $data);

?>
