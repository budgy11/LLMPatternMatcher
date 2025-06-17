    <input type="text" name="search" value="<?php echo htmlspecialchars($search_term); ?>" />
    <input type="submit" value="Search" />
  </form>

  <?php
  if ($result) {
    if (mysql_num_rows($result) > 0) {
      echo "<ul>";
      while ($row = mysql_fetch_assoc($result)) {
        echo "<li>";
        // Display the data from each row
        echo "Name: " . htmlspecialchars($row['name']) . "<br>";
        echo "Description: " . htmlspecialchars($row['description']) . "<br>";
        echo "Link: <a href='" . htmlspecialchars($row['link']) . "'>Link</a><br><br>";
        echo "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found.</p>";
    }
  } else {
    echo "<p>An error occurred while executing the query.</p>";
  }
  ?>
