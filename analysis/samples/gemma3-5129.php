    <input type="text" name="search_term" placeholder="Enter search term" value="<?php echo htmlspecialchars($_GET["search_term"]); ?>">
    <button type="submit">Search</button>
  </form>

  <?php

  // Handle the search request
  if (isset($_GET["search_term"]) && !empty($_GET["search_term"])) {
    $search_term = $_GET["search_term"];

    //  Your search logic goes here.  Replace this placeholder with your actual code.
    //  This example just echoes the search term back for demonstration.

    echo "<p>Searching for: " . htmlspecialchars($search_term) . "</p>";

    // Example: Searching through a simple array of titles. Replace with your data source.
    $titles = ["Apple", "Banana", "Orange", "Grapefruit"];
    if (in_array($search_term, $titles)) {
      echo "<p>Found: " . htmlspecialchars($search_term) . " in the list.</p>";
    } else {
      echo "<p>No results found.</p>";
    }


  }
  ?>
