    <button type="submit" onclick="location.reload()">Search</button>
  </div>

  <?php
    if ($result->num_rows > 0) {
      echo "<div class='results'>";
      while ($row = $result->fetch_assoc()) {
        echo "<div class='result-item'>";
        echo "<p>" . htmlspecialchars($row['name']) . "</p>"; //Assuming 'name' is the product name column
        // Add other product details here as needed
        echo "</div>";
      }
      echo "</div>";
    } else {
      echo "<p>No products found matching your search term.</p>";
    }
  ?>
