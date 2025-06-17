    <button type="submit">Search</button>
  </form>

  <?php
  if ($result) {
    if (mysqli_num_rows($result) > 0) {
      echo "<h2>Search Results:</h2>";
      echo "<ul>";
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>" . htmlspecialchars($row['name']) . " - " . htmlspecialchars($row['price']) . "</li>"; // Adjust for other columns if needed
      }
      echo "</ul>";
    } else {
      echo "<p>No products found matching your search.</p>";
    }
  } else {
    echo "<p>Error executing query.</p>";
  }
  ?>
