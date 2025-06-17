
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// and you want to search through a table called 'products'
// with a column called 'name' that you want to search.

// 1. Get the search term from the form input
if (isset($_GET['search'])) {
  $searchTerm = $_GET['search'];

  // Escape the search term to prevent SQL injection
  $searchTerm = $mysqli->real_escape_string($searchTerm); 

  // 2. Build the SQL query
  $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'"; 

  // 3. Execute the query
  $result = $mysqli->query($sql);

  // 4. Display the results
  if ($result) {
    echo "<form method='get' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>
          <input type='text' name='search' placeholder='Search products...' value='" . $searchTerm . "'>
          <button type='submit'>Search</button>
        </form>";

    echo "<table border='1'>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Price</th>
              <!-- Add other columns from your 'products' table here -->
            </tr>
          </thead>
          <tbody>";

    while ($row = $result->fetch_assoc()) {
      echo "<tr>
              <td>" . $row['id'] . "</td>
              <td>" . $row['name'] . "</td>
              <td>" . $row['price'] . "</td>
              <!-- Add other columns from your 'products' table here -->
            </tr>";
    }

    echo "</tbody>
          </table>";

  } else {
    echo "No results found for '" . $searchTerm . "'.";
  }
} else {
  // If no search term is provided, display the default form
  echo "<form method='get' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>
          <input type='text' name='search' placeholder='Search products...' value=''>
          <button type='submit'>Search</button>
        </form>
        <table border='1'>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Price</th>
              <!-- Add other columns from your 'products' table here -->
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Laptop</td>
              <td>1200</td>
            </tr>
            <tr>
              <td>2</td>
              <td>Mouse</td>
              <td>25</td>
            </tr>
            <tr>
              <td>3</td>
              <td>Keyboard</td>
              <td>75</td>
            </tr>
          </tbody>
        </table>";
}

// Close the database connection (Important!)
$mysqli->close();

?>
