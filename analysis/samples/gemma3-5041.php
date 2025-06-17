

<?php

// Assuming you have a database connection established (e.g., mysqli)
// Let's represent the connection as $conn

// Function to handle the search query
function performSearch($conn, $search_term) {
  // Sanitize the search term - very important for security
  $search_term = mysqli_real_escape_string($conn, $search_term); 

  // Build the SQL query (adjust table and column names accordingly)
  $sql = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%' OR description LIKE '%" . $search_term . "%'"; 

  // Execute the query
  $result = mysqli_query($conn, $sql);

  // Process the results
  if (mysqli_num_rows($result) > 0) {
    echo "<form method='get' action='search_results.php'>
          <input type='text' name='search_term' value='" . htmlspecialchars($search_term) . "' placeholder='Search...'>
          <button type='submit'>Search</button>
          </form>";

    echo "<br>";
    echo "<table border='1'>
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>  
                <!-- Add other columns from your database -->
              </tr>
            </thead>
            <tbody>";

    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr>";
      echo "<td>" . $row['id'] . "</td>";
      echo "<td>" . $row['name'] . "</td>";
      echo "<td>" . $row['description'] . "</td>";
      echo "<td>" . $row['price'] . "</td>";
      echo "</tr>";
    }

    echo "</tbody>
          </table>";

  } else {
    echo "<p>No results found for '" . htmlspecialchars($search_term) . "'</p>";
  }

  mysqli_free_result($result); // Free the result set
}

// Example Usage:
//  Assuming you've received the search term from a form submission

// This would be called after a form submission or other event
// that triggers the search.
// Example of receiving the search term:
// $search_term = $_POST['search_term'];  // Or $_GET['search_term']

// Call the function:
// performSearch($conn, $search_term);


// Example HTML Form (for testing)
?>
