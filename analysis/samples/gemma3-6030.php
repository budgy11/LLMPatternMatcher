<!DOCTYPE html>
<html>
<head>
  <title>Search Example</title>
</head>
<body>

  <h1>Search</h1>

  <form action="search_results.php" method="GET">
    <input type="text" name="search_term" placeholder="Enter search term" style="width: 300px;">
    <button type="submit">Search</button>
  </form>

</body>
</html>

<?php

// 2. PHP Code to Process the Search Query (search_results.php - This is just an example)

// Check if the search term is set in the GET request
if (isset($_GET['search_term'])) {

  // Sanitize the input to prevent SQL injection and other security vulnerabilities
  $search_term = htmlspecialchars(trim($_GET['search_term'])); 

  //  You can now use the $search_term in your search logic.
  //  Example:  Search through a database, array, or other data source.

  //  IMPORTANT:  Replace this with your actual search logic!
  $results = searchDatabase($search_term);  //  Example function - replace with yours

  // Display the results.  This is just a placeholder.
  echo "<h2>Search Results for: " . $search_term . "</h2>";
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
  echo "<p>No search term provided.</p>";
}


// Example function to simulate a database search (Replace this!)
function searchDatabase($term) {
    // In a real application, you would connect to your database here.
    // This is just a placeholder for demonstration.
    $data = [
        "Apple",
        "Banana",
        "Orange",
        "Grape",
        "Strawberry",
        "Pineapple"
    ];
    $results = [];
    foreach ($data as $item) {
      if (stripos($item, $term) !== false) { // Case-insensitive search
        $results[] = $item;
      }
    }
    return $results;
}

?>
