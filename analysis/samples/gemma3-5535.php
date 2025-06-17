    <button type="submit">Search</button>
  </form>

  <?php if (empty($results)) {
    echo "<p>No results found for: " . htmlspecialchars($search_term) . "</p>";
  } else {
    echo "<h2>Search Results:</h2>";
    echo "<ul>";
    foreach ($results as $row) {
      echo "<li>" . htmlspecialchars($row['name']) . " - " . htmlspecialchars($row['description']) . "</li>"; // Adapt based on your database fields
    }
    echo "</ul>";
  }
?>
