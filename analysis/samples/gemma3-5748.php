    <input type="text" name="search_term" placeholder="Search..." value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>">
    <input type="submit" value="Search">
  </form>

  <?php

  // Handle the search term when the form is submitted
  if (isset($_GET['search_term'])) {
    $search_term = htmlspecialchars($_GET['search_term']);

    // Perform your search logic here.  This is just an example.
    //  Replace this with your actual database query or other search implementation.
    $results = performSearch($search_term); // Assuming you have a function named performSearch

    // Display the results.
    if (empty($results)) {
      echo "<p>No results found for: " . $search_term . "</p>";
    } else {
      echo "<h2>Search Results for: " . $search_term . "</h2>";
      echo "<ul>";
      foreach ($results as $result) {
        echo "<li>" . $result . "</li>";
      }
      echo "</ul>";
    }
  }
  ?>
