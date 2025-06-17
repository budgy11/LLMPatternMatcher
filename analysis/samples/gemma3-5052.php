  <input type="text" name="search" placeholder="Search..." value="<?php echo $searchTerm; ?>">
  <button type="submit">Search</button>
</form>

<?php

// Display the results (replace with your desired display logic)
if (!empty($results)) {
  echo "<h2>Search Results:</h2>";
  echo "<table border='1'>";
  echo "<thead><tr><th>Column 1</th><th>Column 2</th><th>...</th></tr></thead>";
  echo "<tbody>";
  foreach ($results as $row) {
    echo "<tr>";
    foreach ($row as $column => $value) {
      echo "<td>" . $value . "</td>";
    }
    echo "</tr>";
  }
  echo "</tbody>";
  echo "</table>";
} else {
  echo "<p>No results found.</p>";
}

?>
