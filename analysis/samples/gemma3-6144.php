    <input type="text" name="search_term" value="<?php echo $searchTerm; ?>" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php
  // Example Search Logic (Replace with your actual search logic)
  if ($searchTerm != "") {
    echo "<h2>Search Results for: " . htmlspecialchars($searchTerm) . "</h2>";
    echo "<p>No results found for: " . htmlspecialchars($searchTerm) . "</p>";

    //  Example:  Dummy Search through an array
    $items = ["apple", "banana", "orange", "grapefruit", "watermelon"];

    if (strpos($searchTerm, "apple") !== false) {
      echo "<p>You might like apples!</p>";
    }

    if (strpos($searchTerm, "banana") !== false) {
      echo "<p>Bananas are a great choice!</p>";
    }

    //  More robust search would use LIKE operator in a database query
  }
?>
