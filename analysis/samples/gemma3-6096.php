  <button type="submit">Search</button>
</form>

<?php

if ($result) {
  if ($result->num_rows > 0) {
    echo "<br>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
      echo "<li>" . htmlspecialchars($row['your_column_name']) . "</li>"; //Escape for XSS
    }
    echo "</ul>";
  } else {
    echo "<p>No results found.</p>";
  }
} else {
  echo "<p>Error executing query.</p>";
}

$db->close(); // Close the database connection
?>
