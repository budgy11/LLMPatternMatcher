    <input type="text" name="search_term" placeholder="Enter search term" value="<?php echo htmlspecialchars($_GET["search_term"]); ?>">
    <button type="submit">Search</button>
  </form>

  <?php

  // Simulate a database search (replace with your actual database query)
  if (isset($_GET['search_term'])) {
    $search_term = htmlspecialchars($_GET['search_term']);

    // Example: Search in a simple array
    $items = array(
      "apple",
      "banana",
      "orange",
      "grapefruit",
      "kiwi"
    );

    $results = [];
    foreach ($items as $item) {
      if (stripos($item, $search_term) !== false) { // Case-insensitive search
        $results[] = $item;
      }
    }

    if (empty($results)) {
      echo "<p>No results found for: " . $search_term . "</p>";
    } else {
      echo "<h2>Search Results for: " . $search_term . "</h2>";
      echo "<ul>";
      foreach ($results as $result) {
        echo "<li>" . $result . "</li>";
      }
      echo "</ul>";
    }
  }

  ?>
