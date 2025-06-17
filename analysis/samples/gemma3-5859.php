      <input type="text" name="search_term" placeholder="Search..." class="search-input">
      <button type="submit" class="search-button">Search</button>
    </form>
  </div>

  <?php
  //  Here you would add your code to process the search_term and display results.
  //  This is just a placeholder to show where the search logic would go.

  if (isset($_GET['search_term'])) {
    $search_term = htmlspecialchars($_GET['search_term']); // Sanitize input

    // Example:  Searching through an array of products (replace with your actual data source)
    $products = [
      "Laptop",
      "Smartphone",
      "Tablet",
      "Wireless Mouse",
      "Keyboard"
    ];

    $results = [];
    foreach ($products as $product) {
      if (strpos(strtolower($product), strtolower($search_term)) !== false) {
        $results[] = $product;
      }
    }

    if (!empty($results)) {
      echo "<p>Search Results: " . implode(", ", $results) . "</p>";
    } else {
      echo "<p>No results found for '" . $search_term . "'.</p>";
    }
  }
?>
