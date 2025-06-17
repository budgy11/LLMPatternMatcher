    <input type="submit" value="Search">
  </form>

  <?php
  if ($result->num_rows > 0) {
    echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Description</th></tr>";
    // Output data of each row.
    while($row = $result->fetch_assoc()) {
      echo "<tr><td>" . $row["id"] . "</td><td>" . $row["name"] . "</td><td>" . $row["description"] . "</td></tr>";
    }
    echo "</table>";
  } else {
    echo "No results found.";
  }
  ?>
