    <input type="text" name="search_term" placeholder="Enter search term" value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>">
    <button type="submit">Search</button>
  </form>

  <?php

  // Handle the search query
  if (isset($_GET['search_term'])) {
    $search_term = htmlspecialchars($_GET['search_term']);

    // Example: Search through a simple array of products
    $products = [
      'Laptop' => 'High-performance laptop',
      'Mouse' => 'Wireless mouse',
      'Keyboard' => 'Mechanical keyboard',
      'Monitor' => '27-inch monitor',
      'Headphones' => 'Noise-canceling headphones'
    ];

    $results = [];

    foreach ($products as $product => $description) {
      if (strpos($description, $search_term) !== false) { // Case-insensitive search
        $results[$product] = $description;
      }
    }

    if (!empty($results)) {
      echo "<h2>Search Results:</h2>";
      echo "<ul>";
      foreach ($results as $product => $description) {
        echo "<li>" . $product . ": " . $description . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found for '" . $search_term . "'.</p>";
    }
  }

  ?>
