
</body>
</html>


<?php

// Basic Search Bar Implementation in PHP

// 1. Get the Search Term from the Form
if (isset($_GET['search'])) {
  $searchTerm = $_GET['search'];
} else {
  $searchTerm = ''; // Or initialize to an empty string if you prefer
}


// 2. Sanitize the Search Term (IMPORTANT for security)
//    - Remove HTML tags to prevent XSS attacks
//    - You might want to also trim whitespace
$searchTerm = htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8');



// 3.  Search Logic (Example - Searching a Simple Array)
//    -  Replace this with your actual search logic based on your data
$data = [
  'apple', 'banana', 'orange', 'grape', 'mango', 'pineapple'
];

$searchResults = [];

if ($searchTerm) {
  foreach ($data as $item) {
    if (stripos($item, $searchTerm) !== false) { // Case-insensitive search
      $searchResults[] = $item;
    }
  }
}

// 4. Output the Form and Results
echo '<form method="GET" action="search_results.php">  <!-- Redirect to a results page -->
      <input type="text" name="search" value="' . htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8') . '"  placeholder="Search...">
      <button type="submit">Search</button>
    </form>';

if (!empty($searchResults)) {
  echo '<h2>Search Results:</h2>';
  echo '<ul>';
  foreach ($searchResults as $result) {
    echo '<li>' . $result . '</li>';
  }
  echo '</ul>';
} else {
  echo '<p>No results found for "' . $searchTerm . '".</p>';
}

?>
