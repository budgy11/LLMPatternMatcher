    <button type="submit">Search</button>
  </form>

  <?php
    if ($result->num_rows > 0) {
      echo "<h2>Search Results:</h2>";
      echo "<ul>";
      // Output each row of the result
      while ($row = $result->fetch_assoc()) {
        echo "<li>";
        // Display the relevant data from the row
        echo htmlspecialchars($row['your_column_name']); // Replace 'your_column_name' with the actual column name you want to display
        echo "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found.</p>";
    }
  ?>
