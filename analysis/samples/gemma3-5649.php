    <button type="submit">Search</button>
  </form>

  <?php
    if ($result) {
      if (mysqli_num_rows($result) > 0) {
        echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Description</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>";
          echo "<td>" . $row['id'] . "</td>";
          echo "<td>" . $row['name'] . "</td>";
          echo "<td>" . $row['description'] . "</td>";
          echo "</tr>";
        }
        echo "</table>";
      } else {
        echo "<p>No products found matching your search term.</p>";
      }
    } else {
      echo "<p>Error executing query.</p>";
    }
    mysqli_free_result($result); // Important: Free the result set
  ?>
