    <input type="text" name="search_term" placeholder="Enter search term" value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>">
    <input type="submit" value="Search">
  </form>

  <?php
  // Example of processing the search term and displaying results
  if (isset($_GET['search_term'])) {
    $search_term = htmlspecialchars($_GET['search_term']);

    // Replace this with your actual search logic
    $results = searchDatabase($search_term);

    if (!empty($results)) {
      echo "<h2>Search Results for: " . $search_term . "</h2>";
      echo "<ul>";
      foreach ($results as $result) {
        echo "<li>" . $result . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found for: " . $search_term . "</p>";
    }
  }
  ?>
