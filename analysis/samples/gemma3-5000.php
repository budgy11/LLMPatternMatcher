    <input type="text" name="search_term" placeholder="Enter a fruit name or description">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search term from the form
  $searchTerm = htmlspecialchars($_POST["searchTerm"]);

  // Sanitize the search term (important for security)
  $searchTerm = trim($searchTerm);
  $searchTerm = strip_tags($searchTerm);


  // Basic validation (optional but recommended)
  if (empty($searchTerm)) {
    $message = "Please enter a search term.";
  } else {
    // Perform your search logic here.  
    // This is just an example, you'll need to adapt it to your data.
    //  Replace this with your actual database query or file search.

    // Example:  Searching an array of products (replace with your data source)
    $products = [
      "apple", "banana", "orange", "grape", "kiwi",
      "computer", "laptop", "tablet", "smartphone"
    ];

    $foundProducts = [];
    foreach ($products as $product) {
      if (stripos($product, $searchTerm) !== false) { // Case-insensitive search
        $foundProducts[] = $product;
      }
    }

    if (empty($foundProducts)) {
      $message = "No results found for '" . $searchTerm . "'.";
    } else {
      $message = "Found " . count($foundProducts) . " results for '" . $searchTerm . "': " . implode(", ", $foundProducts);
    }
  }
}
?>
