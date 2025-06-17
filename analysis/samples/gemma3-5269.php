    <input type="text" name="search_term" placeholder="Search..." value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>">
    <button type="submit">Search</button>
  </form>

  <?php
  // Example: Display results based on the search term (replace with your logic)
  if (isset($_GET['search_term'])) {
    $search_term = htmlspecialchars($_GET['search_term']);

    // Replace this with your actual search logic
    $results = searchDatabase($search_term); 

    if (count($results) > 0) {
      echo "<h2>Search Results for: " . $search_term . "</h2>";
      echo "<ul>";
      foreach ($results as $result) {
        echo "<li>" . $result . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found for '" . $search_term . "'.</p>";
    }
  }
  ?>
