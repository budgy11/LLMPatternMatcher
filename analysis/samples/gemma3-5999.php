        <input type="text" name="search_term" placeholder="Enter search term">
        <button type="submit">Search</button>
    </form>

</body>
</html>


<?php

// Simple search bar implementation in PHP

// Get the search term from the form (assuming a simple form)
if (isset($_GET['search']) && !empty($_GET['search'])) {
  $searchTerm = $_GET['search'];

  // Sanitize the search term - IMPORTANT for security
  $searchTerm = filter_var($searchTerm, FILTER_SANITIZE_STRING);

  // Escape special characters to prevent XSS vulnerabilities
  $searchTerm = htmlspecialchars($searchTerm);

  // Perform the search (replace this with your actual search logic)
  $searchResults = performSearch($searchTerm);

  // Display the search bar and results
  echo '<form action="" method="get">';
  echo 'Search: <input type="text" name="search" value="' . $searchTerm . '">';
  echo '<button type="submit">Search</button>';
  echo '</form>';

  if (!empty($searchResults)) {
    echo '<h2>Search Results:</h2>';
    echo '<ul>';
    foreach ($searchResults as $result) {
      echo '<li>' . $result . '</li>';
    }
    echo '</ul>';
  } else {
    echo '<p>No results found for: ' . $searchTerm . '</p>';
  }
} else {
  // If no search term is provided, display the search bar
  echo '<form action="" method="get">';
  echo 'Search: <input type="text" name="search">';
  echo '<button type="submit">Search</button>';
  echo '</form>';
}

// --- Helper function for performing the search (replace with your logic) ---
function performSearch($searchTerm) {
  // Example:  Check if the search term exists in a database or array.
  // This is a placeholder - adapt to your needs.

  $data = [
    'apple',
    'banana',
    'orange',
    'grapefruit',
    'strawberry'
  ];

  $results = [];
  foreach ($data as $item) {
    if (stripos($item, $searchTerm) !== false) { // Case-insensitive search
      $results[] = $item;
    }
  }

  return $results;
}

?>
