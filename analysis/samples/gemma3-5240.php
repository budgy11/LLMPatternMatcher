
</body>
</html>


<?php

// Function to handle the search
function performSearch($searchTerm, $data) {
  // $data should be an array of arrays, where each inner array represents a row.
  // Example:
  // $data = [
  //   ['name' => 'Apple', 'description' => 'A red fruit'],
  //   ['name' => 'Banana', 'description' => 'A yellow fruit'],
  //   ['name' => 'Orange', 'description' => 'A citrus fruit']
  // ];

  $results = [];

  foreach ($data as $row) {
    // Convert both the search term and the data values to lowercase for case-insensitive search
    $searchTermLower = strtolower($searchTerm);
    $nameLower = strtolower($row['name'] ?? ''); // Handle missing 'name' key gracefully
    $descriptionLower = strtolower($row['description'] ?? '');

    // Check if the search term exists in either the name or description
    if (strpos($nameLower, $searchTermLower) !== false || strpos($descriptionLower, $searchTermLower) !== false) {
      $results[] = $row;
    }
  }

  return $results;
}


// Example usage:

// Sample data (replace this with your actual data)
$myData = [
  ['name' => 'Apple', 'description' => 'A red fruit that is sweet'],
  ['name' => 'Banana', 'description' => 'A yellow fruit'],
  ['name' => 'Orange', 'description' => 'A citrus fruit'],
  ['name' => 'Grape', 'description' => 'Small purple fruits'],
  ['name' => 'Strawberry', 'description' => 'A red fruit, small and juicy']
];

// Get the search term from the user (e.g., from an input field)
$searchTerm = $_GET['search'];  // Retrieve the search term from the URL query string
// OR, if you're getting it from a form:
// $searchTerm = $_POST['search'];

// Handle empty search term
if (empty($searchTerm)) {
  $results = $myData; // Show all data if the search term is empty.  Or you can return an empty array.
} else {
  // Perform the search
  $results = performSearch($searchTerm, $myData);
}


// Display the search results
?>
