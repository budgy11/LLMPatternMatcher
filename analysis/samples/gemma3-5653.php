        <input type="text" name="search" placeholder="Search...">
        <button type="submit">Search</button>
    </form>

</body>
</html>


<?php

// Check if the search form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize the input
  $search_term = htmlspecialchars(trim($_POST["search"]));

  // Basic validation (optional, but recommended)
  if (empty($search_term)) {
    $results = []; // Or display an error message to the user
    $error_message = "Please enter a search term.";
  } else {
    // Your search logic here
    // Example: Searching through an array of products
    $products = [
      "Apple iPhone 13",
      "Samsung Galaxy S22",
      "Google Pixel 6",
      "OnePlus 9 Pro",
      "Apple iPad Air 5th Gen",
    ];

    $results = [];
    foreach ($products as $product) {
      if (stripos($product, $search_term) !== false) { // Case-insensitive search
        $results[] = $product;
      }
    }

    // Display the results
    if (empty($results)) {
      echo "<p>No results found for '$search_term'.</p>";
    } else {
      echo "<ul>";
      foreach ($results as $result) {
        echo "<li>" . $result . "</li>";
      }
      echo "</ul>";
    }
  }
}
?>
