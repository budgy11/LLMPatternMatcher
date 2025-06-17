    <button type="submit">Search</button>
  </form>

  <?php
    if ($result->num_rows > 0) {
      echo "<ul>";
      // Output each row as a list item
      while($row = $result->fetch_assoc()) {
        echo "<li>";
        echo "<strong>" . htmlspecialchars($row['name']) . "</strong> - " . htmlspecialchars($row['description']) . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found.</p>";
    }
  ?>
