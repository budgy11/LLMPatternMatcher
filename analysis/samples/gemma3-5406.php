    <button type="submit">Search</button>
  </form>

  <?php
    if (mysqli_num_rows($result) > 0) {
      // Display the search results
      echo "<h2>Search Results:</h2>";
      echo "<ul>";
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>" . $row['name'] . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No products found matching your search term.</p>";
    }
  ?>
