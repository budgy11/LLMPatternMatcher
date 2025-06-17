    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// ... (Database connection setup as before) ...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = $_POST["search_term"];
  $searchTerm = htmlspecialchars($searchTerm);

  // Prepare the statement (this is the key for security)
  $stmt = mysqli_prepare($conn, "SELECT * FROM your_table WHERE your_column LIKE '%s%'");  // 's' indicates a string parameter

  if ($stmt) {
    // Bind the parameter
    mysqli_stmt_bind_param($stmt, "s", $searchTerm);  // 's' means string

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Process the results (same as before)
    if ($result) {
      echo "<ul>";
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>" . $row['your_column'] . "</li>";
      }
      echo "</ul>";
      mysqli_free_result($result); // Important to free the result set
      mysqli_stmt_close($stmt);
    } else {
      // Handle errors
      echo "Error executing query.";
    }

  } else {
    // Handle errors preparing the statement
    echo "Error preparing statement.";
  }

}
?>
