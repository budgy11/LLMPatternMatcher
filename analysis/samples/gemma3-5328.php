    <button type="submit">Search</button>
  </form>

  <?php
    // Display search results
    if (mysqli_num_rows($result) > 0) {
      echo "<h2>Search Results:</h2>";
      echo "<ul>";
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>Product Name: " . $row['name'] . ", ID: " . $row['id'] . "</li>"; //Adjust keys based on your table structure
      }
      echo "</ul>";
    } else {
      echo "<p>No products found matching your search term.</p>";
    }
  ?>
