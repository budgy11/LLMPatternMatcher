    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php
  // Handle the search query if the form was submitted
  if (isset($_GET['search_term'])) {
    $search_term = $_GET['search_term'];

    // Sanitize the search term (Important for security)
    $search_term = htmlspecialchars(trim($search_term));

    // ***  Replace this with your actual search logic  ***
    // Example:  Search through an array of products
    $products = [
      "Apple iPhone 14 Pro",
      "Samsung Galaxy S23",
      "Google Pixel 7",
      "OnePlus 11"
    ];

    $results = [];
    foreach ($products as $product) {
      if (strpos($product, $search_term) !== false) {
        $results[] = $product;
      }
    }

    // Display the search results
    if (!empty($results)) {
      echo "<h2>Search Results:</h2>";
      echo "<ul>";
      foreach ($results as $result) {
        echo "<li>" . $result . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found for '" . $search_term . "'</p>";
    }
  }
?>
