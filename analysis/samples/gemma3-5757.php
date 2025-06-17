
<!--  Example of the HTML form to submit the search  -->
<form method="post" action="search_results.php">
  <input type="text" name="search_term" placeholder="Search products...">
  <button type="submit">Search</button>
</form>


<?php

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the search term from the form
  $searchTerm = htmlspecialchars($_POST["search_term"]);

  // Sanitize the input -  Important for security!
  $searchTerm = trim($searchTerm); // Remove leading/trailing whitespace
  $searchTerm = filter_var($searchTerm, FILTER_SANITIZE_STRING); // Remove HTML tags and potentially other characters

  // Validate the search term (optional, but recommended)
  if (empty($searchTerm)) {
    $message = "Please enter a search term.";
  } else {
    // Perform the search (replace with your actual search logic)
    // Example:  Searching through an array of products
    $products = [
      "Red T-shirt" => "Description of Red T-shirt",
      "Blue Jeans" => "Description of Blue Jeans",
      "Black Shoes" => "Description of Black Shoes",
    ];

    $results = [];
    foreach ($products as $key => $value) {
      if (stripos($key, $searchTerm) !== false) { // Case-insensitive search
        $results[] = [
          "name" => $key,
          "description" => $value
        ];
      }
    }

    // Display the search results
    if (empty($results)) {
      $message = "No results found for '" . $searchTerm . "'.";
    } else {
      echo "<h2>Search Results for: " . $searchTerm . "</h2>";
      echo "<table border='1'>";
      echo "<tr><th>Name</th><th>Description</th></tr>";
      foreach ($results as $result) {
        echo "<tr>";
        echo "<td>" . $result["name"] . "</td>";
        echo "<td>" . $result["description"] . "</td>";
        echo "</tr>";
      }
      echo "</table>";
    }

  }

}
?>
