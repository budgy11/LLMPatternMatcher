    <input type="submit" value="Search" />
  </form>

  <?php
    if ($result->num_rows > 0) {
      echo "<ul>";
      while ($row = $result->fetch_assoc()) {
        echo "<li>" . $row['name'] . " - " . $row['description'] . "</li>";
      }
      echo "</ul>";
    } else {
      echo "No results found for '" . $searchTerm . "'.";
    }
  ?>
