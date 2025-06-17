    <button type="submit">Search</button>
  </form>

  <?php if (empty($results)) {
    echo "<p>No results found for: " . htmlspecialchars($searchTerm) . "</p>";
  } else { ?>
