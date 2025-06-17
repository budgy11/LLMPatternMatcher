    <input type="text" name="search" value="<?php echo htmlspecialchars($search_term); ?>">
    <input type="submit" value="Search">
  </form>

  <?php

  //  Example:  Searching through an array of data (replace with your data source)
  $data = [
    "apple", "banana", "cherry", "date", "elderberry", "fig",
    "grape", "honeydew", "kiwi", "lemon", "mango", "nectarine",
    "orange", "pear", "raspberry", "strawberry", "tangerine", "watermelon"
  ];

  if ($search_term != "") {
    $found_results = [];
    foreach ($data as $item) {
      if (stripos($item, $search_term) !== false) { // Case-insensitive search
        $found_results[] = $item;
      }
    }

    if (!empty($found_results)) {
      echo "<h2>Search Results:</h2>";
      echo "<ul>";
      foreach ($found_results as $result) {
        echo "<li>" . htmlspecialchars($result) . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found for '" . htmlspecialchars($search_term) . "'.</p>";
    }
  } else {
    echo "<p>Enter your search term above.</p>";
  }

  ?>
