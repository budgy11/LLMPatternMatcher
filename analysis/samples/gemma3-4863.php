
</body>
</html>


       $sql = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'";
       $result = $mysqli->query($sql);
       
   * **Process the Query Results:**  Loop through the `$result` object (from the database query) and display the matching results.  *Always* use prepared statements to prevent SQL injection vulnerabilities.
5. **Add Styling (CSS):** Add CSS to style the search bar and results.

**Important Security Considerations (SQL Injection Prevention):**

* **Prepared Statements:** *Always* use prepared statements with placeholders when constructing SQL queries from user input. This is the *best* defense against SQL injection vulnerabilities.  Here's an example using `mysqli`:



<?php

// Function to handle the search bar
function handleSearch($searchTerm) {
  // Sanitize the search term - VERY IMPORTANT for security
  $searchTerm = filter_var($searchTerm, FILTER_SANITIZE_STRING);

  // Escape the search term - Also important for security
  $searchTerm = htmlspecialchars($searchTerm);

  //  Add the search term to your search logic here
  //  This is a placeholder, you'll need to replace it with your actual search logic.

  // Example:  Simple case-insensitive search within a string (replace with your database/array logic)
  $data = "This is some sample data.  Searching for 'example' will find 'example'.";
  $results = explode(" ", $data); // Split into an array of words

  $found = false;
  foreach ($results as $word) {
    if (stripos($word, $searchTerm) !== false) {
      $found = true;
      break;
    }
  }

  if ($found) {
    echo "<p>Found results for: " . $searchTerm . "</p>";
    //  Display the results here based on the $searchTerm
  } else {
    echo "<p>No results found for: " . $searchTerm . "</p>";
  }
}

?>
