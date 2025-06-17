    <input type="text" name="search" value="<?php echo $searchTerm; ?>">
    <input type="submit" value="Search">
  </form>

  <?php
  if ($result->num_rows > 0) {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
      echo "<li>" . $row['name'] . " - " . $row['description'] . "</li>"; // Adapt to your table columns
    }
    echo "</ul>";
  } else {
    echo "<p>No results found.</p>";
  }
  ?>
