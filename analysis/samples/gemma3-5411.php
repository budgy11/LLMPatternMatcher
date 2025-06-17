

<?php

// Function to handle the search bar
function search_results($search_term) {
  // Sanitize the search term (important for security)
  $search_term = htmlspecialchars(strip_tags($search_term));

  //  Example:  Assume you have an array of data to search through
  $data = [
    ['name' => 'Apple iPhone 14', 'description' => 'A great phone!'],
    ['name' => 'Samsung Galaxy S23', 'description' => 'Powerful and stylish.'],
    ['name' => 'Google Pixel 7', 'description' => 'Excellent camera.'],
    ['name' => 'OnePlus 11', 'description' => 'Fast performance and great value.'],
  ];

  // Perform the search
  $results = [];
  foreach ($data as $item) {
    // Case-insensitive search
    if (stripos($item['name'], $search_term) !== false || stripos($item['description'], $search_term) !== false) {
      $results[] = $item;
    }
  }

  // Return the results
  return $results;
}

// ---  HTML for the search bar  ---
?>
