
</body>
</html>


<?php

// Include the necessary library for the search functionality.
// This is a basic example using a simple array.
// For a real application, you'd typically use a database query.

// Sample data to search through (replace with your actual data source)
$data = [
    ['id' => 1, 'name' => 'Apple', 'description' => 'A delicious fruit'],
    ['id' => 2, 'name' => 'Banana', 'description' => 'A yellow fruit'],
    ['id' => 3, 'name' => 'Orange', 'description' => 'A citrus fruit'],
    ['id' => 4, 'name' => 'Grape', 'description' => 'Small, juicy fruit'],
];


// Function to perform the search
function searchData($data, $searchTerm) {
  $searchTerm = strtolower($searchTerm); // Convert to lowercase for case-insensitive search
  $results = [];

  foreach ($data as $item) {
    $name = strtolower($item['name']);
    $description = strtolower($item['description']);

    if (strpos($name, $searchTerm) !== false || strpos($description, $searchTerm) !== false) {
      $results[] = $item;
    }
  }

  return $results;
}


// Handle the search form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = $_POST["search_term"];

  if (isset($searchTerm)) {
    $searchResults = searchData($data, $searchTerm);

    // Display the results
    echo "<h2>Search Results for: " . htmlspecialchars($searchTerm) . "</h2>";
    if (empty($searchResults)) {
      echo "<p>No results found.</p>";
    } else {
      echo "<ul>";
      foreach ($searchResults as $result) {
        echo "<li>ID: " . $result['id'] . ", Name: " . htmlspecialchars($result['name']) . ", Description: " . htmlspecialchars($result['description']) . "</li>";
      }
      echo "</ul>";
    }
  }
}
?>
