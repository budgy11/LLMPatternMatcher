    <input type="text" name="search_term" value="<?php echo isset($search_term) ? htmlspecialchars($search_term) : ''; ?>">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// and a table named 'products' with a 'name' column.

// Get the search query from the form
$search_term = isset($_POST['search']) ? $_POST['search'] : '';

// Sanitize the search term - VERY IMPORTANT!
$search_term = htmlspecialchars(trim($search_term)); //Escapes HTML and removes whitespace

// Basic search logic
if ($search_term != '') {
  // Construct the SQL query (Example using mysqli)
  $query = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'";

  // Execute the query
  $result = mysqli_query($connection, $query);

  // Display the results
  if (mysqli_num_rows($result) > 0) {
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<li>" . $row['name'] . "</li>";
    }
    echo "</ul>";
  } else {
    echo "<p>No products found matching your search.</p>";
  }
}
?>
