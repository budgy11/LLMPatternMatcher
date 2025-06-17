    <button type="submit">Search</button>
  </form>

  <?php
    if ($result->num_rows > 0) {
      echo "<table border='1'>";
      while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
        echo "</tr>";
      }
      echo "</table>";
    } else {
      echo "<p>No results found for '" . htmlspecialchars($searchTerm) . "'</p>";
    }
  ?>
