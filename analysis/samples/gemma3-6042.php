  <button type="submit">Search</button>
</form>

<?php

// Process the search form submission
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  // Get the search term from the form
  $searchTerm = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

  // Perform the search (replace with your actual search logic)
  if ($searchTerm != "") {
    // Example: Search through a simple array
    $items = ["apple", "banana", "orange", "grape", "kiwi"];
    $results = [];

    foreach ($items as $item) {
      if (stripos($item, $searchTerm) !== false) { // Use stripos for case-insensitive searching
        $results[] = $item;
      }
    }

    // Display the results
    if (count($results) > 0) {
      echo "<h2>Search Results:</h2>";
      echo "<ul>";
      foreach ($results as $result) {
        echo "<li>" . $result . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found for '" . $searchTerm . "'.</p>";
    }
  }
?>
