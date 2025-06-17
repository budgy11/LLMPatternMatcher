    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php if (empty($searchResults)) {
    echo "<p>No results found.</p>";
  } else {
    echo "<h2>Search Results:</h2>";
    echo "<ul>";
    foreach ($searchResults as $result) {
      echo "<li>" . $result . "</li>";
    }
    echo "</ul>";
  } ?>
