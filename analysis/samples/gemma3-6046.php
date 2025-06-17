    <input type="submit" value="Search">
  </form>

  <?php if (empty($search_results)) {
    echo "<p>No results found.</p>";
  } else {
    echo "<ul>";
    foreach ($search_results as $result) {
      echo "<li>ID: " . $result['id'] . ", " . $result['title'] . "</li>";
    }
    echo "</ul>";
  } ?>
