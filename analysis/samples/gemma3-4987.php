
  <?php
  if (mysqli_num_rows($result) > 0) {
    echo "<table border='1'>";
    echo "<tr><th>Column 1</th><th>Column 2</th><th>Column 3</th></tr>"; // Adjust column names

    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr>";
      // Access the column values from the $row array
      echo "<td>" . htmlspecialchars($row['column1']) . "</td>";
      echo "<td>" . htmlspecialchars($row['column2']) . "</td>";
      echo "<td>" . htmlspecialchars($row['column3']) . "</td>";
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "<p>No results found for: " . htmlspecialchars($search_term) . "</p>";
  }
  ?>
