    <button type="submit">Search</button>
  </form>

  <?php
    if ($result) {
      // Display the results
      echo "<h2>Search Results for: " . htmlspecialchars($searchTerm) . "</h2>";
      echo "<table border='1'>";
      echo "<tr><th>Column 1</th><th>Column 2</th><th>...</th></tr>"; // Adjust column names
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        foreach ($row as $column => $value) {
          echo "<td>" . htmlspecialchars($value) . "</td>";  // Escape again for display
        }
        echo "</tr>";
      }
      echo "</table>";

    } else {
      echo "<p>No results found.</p>";
    }
  ?>
