

<?php

// This is a simplified example. For a production environment,
// you would likely use a more robust search library or framework.

// Assume you have a database connection established
// $db = new mysqli("localhost", "username", "password", "database_name");
// if (!$db) {
//   die("Connection failed");
// }

// Function to search data (replace with your actual database query)
function searchData($searchTerm, $data) {
  $results = [];
  foreach ($data as $item) {
    // Assuming $item is an associative array with a 'title' field
    if (stripos($item['title'], $searchTerm) !== false) { // Case-insensitive search
      $results[] = $item;
    }
  }
  return $results;
}

// Example Data (replace with your data source)
$my_data = [
    ['id' => 1, 'title' => 'The Lord of the Rings'],
    ['id' => 2, 'title' => 'Pride and Prejudice'],
    ['id' => 3, 'title' => 'To Kill a Mockingbird'],
    ['id' => 4, 'title' => 'The Hitchhiker\'s Guide to the Galaxy'],
    ['id' => 5, 'title' => '1984']
];


// Handle Search Input
if (isset($_GET['search']) && !empty($_GET['search'])) {
  $searchTerm = $_GET['search'];

  // Perform the search
  $searchResults = searchData($searchTerm, $my_data);

  // Display the results
  echo "<h2>Search Results for: " . htmlspecialchars($searchTerm) . "</h2>";
  if (empty($searchResults)) {
    echo "<p>No results found.</p>";
  } else {
    echo "<ul>";
    foreach ($searchResults as $result) {
      echo "<li>" . htmlspecialchars($result['title']) . " (ID: " . $result['id'] . ")</li>";
    }
    echo "</ul>";
  }
} else {
  // Display the search form
  echo "<h2>Search</h2>";
  echo "<form method='get'>";
  echo "<input type='text' name='search' placeholder='Enter search term'>";
  echo "<button type='submit'>Search</button>";
  echo "</form>";
}

?>
