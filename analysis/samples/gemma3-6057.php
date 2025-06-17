    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php
  // **Process the Search Form**

  if (isset($_GET['search_term'])) {
    $search_term = htmlspecialchars($_GET['search_term']);  // Sanitize input
    $results = searchDatabase($search_term, 'name');  // Call your search function

    if (!empty($results)) {
      echo "<h2>Search Results:</h2>";
      echo "<ul>";
      foreach ($results as $result) {
        echo "<li>" . htmlspecialchars($result['name']) . " - " . htmlspecialchars($result['description']) . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found.</p>";
    }
  }
  ?>
