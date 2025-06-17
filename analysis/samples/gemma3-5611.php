
</body>
</html>


<?php

// This is a basic example, customize it to your needs

// Get the search term from the input field (using $_GET for simplicity, you can use $_POST as well)
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent XSS vulnerabilities
$search_term = htmlspecialchars($search_term); // Converts special characters to HTML entities

//  You'll need to replace this with your actual data source.
//  This example uses a simple array.
$data = [
  "apple",
  "banana",
  "orange",
  "grape",
  "kiwi",
  "strawberry",
  "mango",
];


// Perform the search
$results = [];
if ($search_term) {
  foreach ($data as $item) {
    if (stripos($item, $search_term)) { // case-insensitive search
      $results[] = $item;
    }
  }
}

?>
