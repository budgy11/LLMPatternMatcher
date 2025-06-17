    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php

  // Check if the search term is submitted
  if (isset($_GET['search_term'])) {
    $search_term = htmlspecialchars($_GET['search_term']);

    // Perform your search logic here
    // Example: Search in a database, an array, or a file

    $results = search($search_term); // Call your search function

    if (empty($results)) {
      echo "<p>No results found.</p>";
    } else {
      echo "<h2>Search Results:</h2>";
      echo "<ul>";
      foreach ($results as $result) {
        echo "<li>" . $result . "</li>";
      }
      echo "</ul>";
    }
  }

  ?>
