

<?php

// Example: Simple Search Bar in PHP

// Check if the search term is submitted
if (isset($_GET['search']) && isset($_GET['submit'])) {
  // Get the search term from the GET variable 'search'
  $searchTerm = $_GET['search'];

  // Sanitize the search term (Important for security!)
  $searchTerm = htmlspecialchars(trim($searchTerm));

  // You can add your logic here to search through data (e.g., a database, array, etc.)
  // For this example, we'll just display the search term.

  // Example:  Search through an array of products
  $products = [
    'Laptop - 15 inch' => 'https://example.com/laptop1',
    'Smartphone - Latest Model' => 'https://example.com/smartphone',
    'Wireless Mouse' => 'https://example.com/mouse',
    'Gaming Keyboard' => 'https://example.com/keyboard',
  ];

  $searchResults = [];
  foreach ($products as $key => $value) {
    if (strpos($key, $searchTerm) !== false || strpos($value, $searchTerm) !== false) {
      $searchResults[$key] = $value;
    }
  }

  $resultsHtml = '';
  if (!empty($searchResults)) {
    $resultsHtml = '<ul>';
    foreach ($searchResults as $key => $value) {
      $resultsHtml .= '<li><a href="' . $value . '">' . $key . '</a></li>';
    }
    $resultsHtml .= '</ul>';
  }

  // Display the search bar and results
  echo '<form method="get" action="">
          <input type="text" name="search" placeholder="Search..." value="' . $searchTerm . '">
          <input type="submit" name="submit" value="Search">
        </form>
        ' . $resultsHtml;


} else {
  // If no search term is submitted, display the search bar
  echo '<form method="get" action="">
          <input type="text" name="search" placeholder="Search..." value="">
          <input type="submit" name="submit" value="Search">
        </form>';
}

?>
