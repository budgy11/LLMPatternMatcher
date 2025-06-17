    <input type="text" name="search" placeholder="Search..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <input type="submit" value="Search">
  </form>

  <?php
  // Example:  Search through a simple array
  $items = ["apple", "banana", "orange", "grapefruit", "kiwi"];

  if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $searchResults = array_filter($items, function($item) use ($searchTerm) {
      return stripos($item, $searchTerm) !== false; //Case-insensitive search
    });

    if (!empty($searchResults)) {
      echo "<h2>Search Results:</h2>";
      echo "<ul>";
      foreach ($searchResults as $item) {
        echo "<li>" . htmlspecialchars($item) . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found for '" . htmlspecialchars($searchTerm) . "'.</p>";
    }
  }
?>
