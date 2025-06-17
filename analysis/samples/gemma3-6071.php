  <button type="submit">Search</button>
</form>

<?php

if ($result->num_rows > 0) {
  echo "<ul>";
  while($row = $result->fetch_assoc()) {
    echo "<li>" . $row["your_column_name"] . "</li>"; // Replace with actual column name
  }
  echo "</ul>";
} else {
  echo "No results found.";
}

?>
