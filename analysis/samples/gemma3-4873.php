    <button type="submit">Search</button>
  </form>

  <?php
  if ($result->num_rows > 0) {
    echo "<ul>";
    // Output the search results
    while($row = $result->fetch_assoc()) {
      echo "<li>" . $row["your_column_name"] . "</li>"; //Replace 'your_column_name'
    }
    echo "</ul>";
  } else {
    echo "<p>No results found.</p>";
  }
  ?>
