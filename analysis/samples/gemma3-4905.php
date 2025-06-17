    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php
    // This section processes the search term and performs a basic search
    if (isset($_GET['search_term'])) {
      $search_term = htmlspecialchars($_GET['search_term']); // Sanitize input

      // Basic example:  Search within a simple array of products
      $products = [
        "Red T-Shirt" => "Description for Red T-Shirt",
        "Blue Jeans" => "Description for Blue Jeans",
        "Leather Jacket" => "Description for Leather Jacket",
        "Black Shoes" => "Description for Black Shoes"
      ];

      $results = [];

      foreach ($products as $product_name => $description) {
        if (stripos($product_name, $search_term) !== false) {  //Case-insensitive search
          $results[$product_name] = $description;
        }
      }

      if (!empty($results)) {
        echo "<h2>Search Results:</h2>";
        echo "<ul>";
        foreach ($results as $product_name => $description) {
          echo "<li><strong>$product_name</strong>: $description</li>";
        }
        echo "</ul>";
      } else {
        echo "<p>No results found for '$search_term'.</p>";
      }
    }
  ?>
