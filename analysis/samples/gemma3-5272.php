  <button type="submit">Search</button>
</form>

<?php

// Display the search results
if (mysqli_num_rows($result) > 0) {
  echo "<h2>Search Results:</h2>";
  while ($row = mysqli_fetch_assoc($result)) {
    echo "<div>";
    echo "<h3>" . htmlspecialchars($row['name']) . "</h3>"; // Use htmlspecialchars for security
    echo "<p>" . htmlspecialchars($row['description']) . "</p>";  // Display other relevant fields here
    echo "</div>";
  }
} else {
  echo "<p>No results found.</p>";
}

mysqli_close($conn);
?>
