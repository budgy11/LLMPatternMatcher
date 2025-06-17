
<form action="search.php" method="GET">
  <input type="text" name="search_term" placeholder="Search..." style="width: 300px;">
  <button type="submit">Search</button>
</form>

<?php

// This is the PHP code that handles the search.
//  - 'search.php' is the file that will process the search term.
//  - We're using the 'GET' method, meaning the search term will be added to the URL.

// Example:  Getting the search term from the URL
if (isset($_GET['search_term'])) {
  $search_term = $_GET['search_term'];

  // ***  IMPORTANT: Sanitize the search term ***
  // This is crucial to prevent security vulnerabilities like SQL injection.
  $search_term = htmlspecialchars(trim($search_term));  //Convert to HTML entity and strip whitespace

  // ***  Now, you'll need to implement your search logic here. ***
  // This is just a placeholder example.  Replace this with your actual
  // search code (e.g., database query, array search, etc.).

  echo "<p>You searched for: " . $search_term . "</p>";

  // ***  Example Search (Array Search - for demonstration only) ***
  // This is a very basic search and should NOT be used in production
  // with user-provided input directly.  It's here to illustrate the idea.
  $data = array(
    'apple',
    'banana',
    'orange',
    'grape',
    'watermelon'
  );

  if (in_array($search_term, $data)) {
    echo "<p>Found: " . $search_term . "</p>";
  } else {
    echo "<p>No results found.</p>";
  }

} else {
  // If no search term was provided, you could display a default message.
  echo "<p>Please enter a search term.</p>";
}

?>
