

function searchDatabase($searchTerm) {
    // Case-insensitive search using stripos()
    $results = [];
    $searchTerm = strtolower($searchTerm); // Convert to lowercase for consistency

    // Assuming your database column is called "name"
    // You'd need to adjust this to your actual field name
    $query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
    //  ... execute the query ...

    // ... (rest of the function remains the same)
}


<?php

// Sample data (replace with your actual data source)
$items = [
  "apple",
  "banana",
  "orange",
  "grape",
  "mango",
  "strawberry",
  "pineapple",
  "watermelon"
];

// Function to handle the search
function performSearch($search_term, $items) {
  $results = [];

  // Convert search term to lowercase for case-insensitive search
  $search_term = strtolower($search_term);

  foreach ($items as $item) {
    // Convert item to lowercase for case-insensitive comparison
    $item_lower = strtolower($item);

    // Check if the search term is a substring of the item
    if (strpos($item_lower, $search_term) !== false) {
      $results[] = $item; // Add the original item to the results
    }
  }

  return $results;
}

// Get the search term from the form (if submitted)
if (isset($_GET['search']) && !empty($_GET['search'])) {
  $search_term = $_GET['search'];
  $results = performSearch($search_term, $items);

  // Display the results
  echo "<h3>Search Results:</h3>";
  if (empty($results)) {
    echo "<p>No results found.</p>";
  } else {
    echo "<ul>";
    foreach ($results as $result) {
      echo "<li>" . $result . "</li>";
    }
    echo "</ul>";
  }
} else {
  // Display the search form
  echo "<form method='get' action=''>";
  echo "<input type='text' name='search' placeholder='Enter search term'>";
  echo "<button type='submit'>Search</button>";
  echo "</form>";
}
?>
