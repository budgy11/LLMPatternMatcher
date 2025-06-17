

<?php

// Function to handle the search bar
function search_results($search_term) {
  // Sanitize the search term - important for security
  $search_term = htmlspecialchars(trim($search_term)); 

  // Perform the search - this is where you'll connect to your data source

  // Example: Searching through an array of products
  $products = [
    "apple pie" => "Delicious apple pie recipe",
    "banana bread" => "Moist banana bread recipe",
    "chocolate cake" => "Rich chocolate cake recipe",
    "strawberry shortcake" => "Classic strawberry shortcake recipe",
    "blueberry muffin" => "Perfect blueberry muffin recipe",
  ];

  $results = [];
  foreach ($products as $key => $value) {
    if (stripos($key, $search_term) !== false) { // Case-insensitive search
      $results[] = [
        "name" => $key,
        "description" => $value,
      ];
    }
  }

  return $results;
}


// Get the search term from the form (if submitted)
$search_term = isset($_GET['search']) ? $_GET['search'] : ''; 

// Perform the search when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $results = search_results($search_term);
}


?>
