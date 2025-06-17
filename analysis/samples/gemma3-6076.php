

<?php

// Assuming you're in a web environment (e.g., using a web server like Apache)

// 1. Get the Search Query from the Form
if (isset($_GET['search']) && !empty($_GET['search'])) {
  $search_term = $_GET['search'];
} else {
  $search_term = ''; // Reset if nothing is entered
}

// 2. Sanitize the Search Term (Important for Security!)
$search_term = htmlspecialchars($search_term); // Prevents XSS attacks.  This is crucial.
// You can add more sanitization here, depending on where the data comes from.

// 3.  Search Logic (Replace this with your actual search implementation)
// This is a simple example.  You'll likely want to use a database or other data source.

$results = [];
if ($search_term != '') {
    // Replace this with your database query or data source search logic
    // This is just a placeholder that simulates a search based on the term.
    // Example:
    if (strpos($search_term, 'apple') !== false) {
      $results[] = 'Found apple!';
    }
    if (strpos($search_term, 'banana') !== false) {
      $results[] = 'Found banana!';
    }
}

// 4.  Display the Search Form and Results

echo '<form action="" method="get">'; // Empty action means it will automatically redirect
echo 'Search: <input type="text" name="search" value="' . htmlspecialchars($search_term) . '" />';
echo '<input type="submit" value="Search">';
echo '</form>';

if (!empty($results)) {
    echo '<h2>Search Results:</h2>';
    echo '<ul>';
    foreach ($results as $result) {
        echo '<li>' . $result . '</li>';
    }
    echo '</ul>';
} else {
    echo '<p>No results found for "' . htmlspecialchars($search_term) . '"</p>';
}

?>
