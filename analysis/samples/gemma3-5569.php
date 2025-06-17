    <input type="text" name="search_term" placeholder="Enter search term" value="<?php echo htmlspecialchars($searchTerm); ?>">
    <button type="submit">Search</button>
  </form>

  <?php if (!empty($searchTerm)) {
    echo "<h2>Search Results for: " . htmlspecialchars($searchTerm) . "</h2>";
    if (empty($searchResults)) {
      echo "<p>No results found.</p>";
    } else {
      echo "<ul>";
      foreach ($searchResults as $result) {
        echo "<li>" . htmlspecialchars($result) . "</li>";
      }
      echo "</ul>";
    }
  } ?>
