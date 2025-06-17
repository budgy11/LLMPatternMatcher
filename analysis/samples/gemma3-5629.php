    <input type="text" name="search_term" value="<?php echo htmlspecialchars($_SESSION["search_term"]); ?>" placeholder="Enter search term...">
    <button type="submit">Search</button>
  </form>

  <?php
  // Display search results (if any)
  if (!empty($_SESSION["search_results"])) {
    echo "<h2>Search Results:</h2>";
    echo "<ul>";
    foreach ($searchResults as $result) {
      echo "<li>" . $result . "</li>";
    }
    echo "</ul>";
  }
  ?>
