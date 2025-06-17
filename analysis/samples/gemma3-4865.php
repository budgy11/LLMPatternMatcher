
</body>
</html>


<?php

// Assuming you have a search term from a form submission or some other input

// 1. Get the search term (Example from a form)
$searchTerm = isset($_POST['search_term']) ? $_POST['search_term'] : '';

// 2. Sanitize and Prepare the Search Term (IMPORTANT for security)
$searchTerm = htmlspecialchars(trim($searchTerm)); // Converts HTML entities and removes whitespace

// 3.  Build your database query (Example: Searching in a simple array)
$results = [];

//  Example database - Replace this with your actual database connection and query
$data = [
    'apple', 'banana', 'orange', 'grape', 'kiwi', 'mango',
    'pear', 'pineapple', 'strawberry', 'watermelon', 'blueberry'
];

//  Example query -  Adjust this to your database and search criteria
if (!empty($searchTerm)) {
  $searchTerm = strtolower($searchTerm); // Convert to lowercase for case-insensitive search
  foreach ($data as $item) {
    if (stristr($item, $searchTerm)) { // stristr is case-insensitive
      $results[] = $item;
    }
  }
}


// 4. Output the Search Results

echo '<form method="post" action="">
  <input type="text" name="search_term" placeholder="Search...">
  <button type="submit">Search</button>
</form>';

if (!empty($results)) {
  echo '<h2>Search Results:</h2>';
  echo '<ul>';
  foreach ($results as $result) {
    echo '<li>' . $result . '</li>';
  }
  echo '</ul>';
} else {
  echo '<p>No results found for: ' . $searchTerm . '</p>';
}

?>
