  <input type="submit" value="Search">
</form>

<?php
if (mysqli_num_rows($result) > 0) {
  // Output data of each row
  while($row = mysqli_fetch_assoc($result)) {
    echo "<p>ID: " . $row['id'] . " - " . $row['your_column'] . "</p>";  // Adjust 'id' and 'your_column'
  }
} else {
  echo "<p>No results found.</p>";
}
?>
