    <input type="text" name="search" placeholder="Enter search term" value="<?php echo $searchTerm; ?>">
    <button type="submit">Search</button>
  </form>

  <?php

  // Perform the search query (adjust this to your specific database structure)
  if ($searchTerm) {
    $sql = "SELECT * FROM your_table WHERE title LIKE '%" . $searchTerm . "%'"; // Example: search in a 'title' column
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // Output each row of the search result
      while($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "Title: " . htmlspecialchars($row["title"]) . "<br>"; // Sanitize output too!
        echo "Description: " . htmlspecialchars($row["description"]) . "<br>";
        // Add other fields as needed
        echo "</div>";
      }
    } else {
      echo "<p>No results found.</p>";
    }
  }

  ?>
