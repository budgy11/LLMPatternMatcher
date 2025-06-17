    <input type="text" name="search_term" placeholder="Enter search term" value="<?php echo $searchTerm; ?>">
    <button type="submit">Search</button>
  </form>

  <?php
    if ($result) {
      if (mysqli_num_rows($result) > 0) {
        echo "<h2>Search Results:</h2>";
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<h3>" . $row['name'] . "</h3>";
          echo "<p>" . $row['description'] . "</p>";  // Adjust based on your table fields
          // Add other fields to display as needed
          echo "<hr>";
        }
      } else {
        echo "<p>No results found for '" . $searchTerm . "'.</p>";
      }
    } else {
      echo "<p>Error: " . mysqli_error($db_connection) . "</p>";
    }
  ?>
