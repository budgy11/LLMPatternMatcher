
</body>
</html>


<?php

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Sanitize and validate the search term (important for security)
  $searchTerm = htmlspecialchars(trim($searchTerm)); // Prevents XSS, removes whitespace

  //  You can add more validation here, e.g., character limits, allowed characters
  //  if ($searchTerm == "") {
  //    $results = [];  // Empty results if nothing is searched.
  //    echo "<p>Please enter a search term.</p>";
  //    $displayResults = false;
  //  } else {
  //     $displayResults = true;
  //  }



  // Here, you would typically query a database or search through an array of data
  // based on the $searchTerm.
  //  For demonstration, let's create a simple array of data.
  $data = [
    ["id" => 1, "name" => "Apple"],
    ["id" => 2, "name" => "Banana"],
    ["id" => 3, "name" => "Orange"],
    ["id" => 4, "name" => "Grape"],
  ];

  // Search through the data array
  $searchResults = [];
  foreach ($data as $item) {
    if (strpos(strtolower($item["name"]), strtolower($searchTerm)) !== false) {
      $searchResults[] = $item;
    }
  }

  // Display the results
  echo "<h2>Search Results for: " . $searchTerm . "</h2>";
  if (empty($searchResults)) {
    echo "<p>No results found.</p>";
  } else {
    echo "<ul>";
    foreach ($searchResults as $result) {
      echo "<li>ID: " . $result["id"] . ", Name: " . $result["name"] . "</li>";
    }
    echo "</ul>";
  }
}
?>
