    <input type="text" name="search_term" placeholder="Enter search term...">
    <button type="submit">Search</button>
  </form>

  <?php

  // Handle the search request
  if (isset($_GET['search_term']) && !empty($_GET['search_term'])) {
    $search_term = htmlspecialchars($_GET['search_term']); // Sanitize input

    // Example: Search in a database (replace with your actual database query)
    // This is a simplified example - adjust the query to match your database schema
    $results = searchDatabase($search_term); 

    if (!empty($results)) {
      echo "<h2>Search Results:</h2>";
      echo "<ul>";
      foreach ($results as $result) {
        echo "<li>" . $result . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found for: " . $search_term . "</p>";
    }
  }

  // Function to simulate searching a database (replace with your actual database query)
  function searchDatabase($term) {
    // This is just a placeholder.  Replace this with your database query logic.
    // This simulates retrieving results from a database.
    $results = [
      "Product A",
      "Product B - Contains " . $term,
      "Another Product - related to " . $term,
      "Product C",
    ];
    return $results;
  }

?>
