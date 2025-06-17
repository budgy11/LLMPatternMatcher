

<?php

// Sample data (replace with your actual data source - database, array, etc.)
$data = [
    "apple", "banana", "cherry", "date", "elderberry",
    "fig", "grape", "honeydew", "indian plum", "jackfruit",
    "kiwi", "lemon", "mango", "nectarine", "orange", "papaya",
    "pear", "quince", "raspberry", "strawberry", "tangerine"
];


// Check if the search form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search term from the form
  $searchTerm = trim($_POST["search_term"]);

  // Validate the search term (optional, but recommended)
  if (empty($searchTerm)) {
    $results = []; // Or display an error message
  } else {
    $results = [];
    foreach ($data as $item) {
      if (stripos($item, $searchTerm) !== false) { // Case-insensitive search
        $results[] = $item;
      }
    }
  }
} else {
  // Initialize results array (important if the form hasn't been submitted)
  $results = [];
}


?>
