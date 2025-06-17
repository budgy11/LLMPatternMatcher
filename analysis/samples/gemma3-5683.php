    <input type="text" name="search" value="<?php echo $searchTerm; ?>">
    <button type="submit">Search</button>
  </form>

  <?php
  if ($result->num_rows > 0) {
    echo "<h2>Search Results:</h2>";
    while ($row = $result->fetch_assoc()) {
      echo "<p>" . $row["name"] . " - " . $row["description"] . "</p>"; // Adjust column names
    }
  } else {
    echo "<p>No products found matching your search.</p>";
  }
  ?>
