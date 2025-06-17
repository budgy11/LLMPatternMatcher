    <input type="text" name="search" placeholder="Enter product name..." value="<?php echo $searchTerm; ?>">
    <input type="submit" value="Search">
  </form>

  <?php
  if ($result->num_rows > 0) {
    echo "<h2>Search Results:</h2>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
      echo "<li>" . $row["name"] . " - " . $row["description"] . "</li>"; // Adjust column names as needed
    }
    echo "</ul>";
  } else {
    echo "<p>No products found matching your search.</p>";
  }
  ?>
