    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php

  // Handle the search query
  if (isset($_GET['search_term']) && !empty($_GET['search_term'])) {
    $search_term = htmlspecialchars($_GET['search_term']); // Sanitize the input

    // *** Example: Search in a database table named 'products' with a 'name' column ***
    //  Adjust this part to your specific database and table structure.

    // *** IMPORTANT: Replace this with your actual database query ***
    $sql = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'";

    // Example using mysqli:
    $result = mysqli_query($db, $sql);  // Assuming $db is your database connection

    if (mysqli_num_rows($result) > 0) {
      echo "<h2>Search Results:</h2>";
      echo "<ul>";
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>" . htmlspecialchars($row['name']) . " - " . htmlspecialchars($row['description']) . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found for '" . htmlspecialchars($search_term) . "'.</p>";
    }

  }

  ?>
