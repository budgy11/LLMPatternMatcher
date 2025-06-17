    <input type="text" name="search" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php
  if ($result->num_rows > 0) {
    echo "<ul>";
    while($row = $result->fetch_assoc()) {
      echo "<li>ID: " . $row['id'] . ", Name: " . $row['name'] . ", Description: " . $row['description'] . "</li>";
    }
    echo "</ul>";
  } else {
    echo "<p>No products found matching your search.</p>";
  }
  ?>
