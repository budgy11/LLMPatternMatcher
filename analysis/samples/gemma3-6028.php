    <input type="text" name="search_term" placeholder="Search..." value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>">
    <input type="submit" value="Search">
  </form>

  <?php
  // Example: Processing the search term (replace with your actual logic)
  if (isset($_GET['search_term'])) {
    $search_term = htmlspecialchars($_GET['search_term']);
    echo "<p>You searched for: " . $search_term . "</p>";

    // Example:  Simple string matching (replace with your actual search logic)
    if (strpos($search_term, 'example') !== false) {
      echo "<p>Found a match for 'example'!</p>";
    } else {
      echo "<p>No results found.</p>";
    }
  }
  ?>
