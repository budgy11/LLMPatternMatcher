      <input type="text" name="search" placeholder="Search..." value="<?php echo $searchTerm; ?>">
      <button type="submit">Search</button>
    </form>
  </div>

  <?php
  // Example database query (replace with your actual query)
  if ($searchTerm) {
    $sql = "SELECT * FROM your_table WHERE your_column LIKE '%" . $searchTerm . "%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // Output each row of the result
      while($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<strong>" . $row["your_column"] . "</strong> - " . $row["another_column"] . "<br>";
        echo "</div>";
      }
    } else {
      echo "<p>No results found.</p>";
    }
  }
  ?>
