    <input type="text" name="search_term" placeholder="Enter product name..." value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>">
    <button type="submit">Search</button>
  </form>

  <?php

  // Handle the search request
  if (isset($_GET['search_term'])) {
    $search_term = htmlspecialchars($_GET['search_term']);

    // SQL query -  IMPORTANT:  Sanitize and escape your inputs properly!
    $query = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'";

    // Execute the query
    $result = $db->query($query);

    // Display the search results
    if ($result->num_rows > 0) {
      echo "<h2>Search Results:</h2>";
      while ($row = $result->fetch_assoc()) {
        echo "<p>Product Name: " . $row['name'] . "</p>";
        // You can display other product details here (e.g., price, description)
      }
    } else {
      echo "<p>No products found matching your search term.</p>";
    }
  }

  ?>
