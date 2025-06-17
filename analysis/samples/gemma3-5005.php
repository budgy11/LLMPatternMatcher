  <input type="submit" value="Search">
</form>

<?php
if (mysqli_num_rows($result) > 0) {
  echo "<ul>";
  while ($row = mysqli_fetch_assoc($result)) {
    echo "<li>" . $row[$columnName] . "</li>"; // Adjust based on your data
  }
  echo "</ul>";
} else {
  echo "<p>No results found for '" . $searchTerm . "'.</p>";
}

// Clean up - Close the database connection (important!)
mysqli_close($db);

?>
