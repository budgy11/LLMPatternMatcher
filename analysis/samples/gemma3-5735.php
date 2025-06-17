  <label for="search">Search:</label>
  <input type="text" id="search" name="search" placeholder="Enter search term">
  <button type="submit">Search</button>
</form>


function performSearch($searchTerm) {
  $data = [
    "apple",
    "banana",
    "orange",
    "grapefruit",
    "pineapple"
  ];
  $results = [];
  foreach ($data as $item) {
    if (stripos($item, $searchTerm) !== false) { // Case-insensitive search
      $results[] = $item;
    }
  }
  return $results;
}


<?php

// Check if the search form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the search term from the form
    $searchTerm = $_POST["search_term"];

    // Sanitize the search term (important for security)
    $searchTerm = htmlspecialchars(trim($searchTerm)); 

    // You can do something with the search term here, such as:
    // 1.  Database query
    // 2.  File search
    // 3.  Simple string comparison within the current page

    // Example:  Display the search term in a simple alert
    echo "<script>alert('Searching for: " . $searchTerm . "');</script>";
    // or display in a div
    // echo "<div class='search-results'>You searched for: " . $searchTerm . "</div>";
}
?>
