        <input type="text" name="search_term" placeholder="Enter search term...">
        <button type="submit">Search</button>
    </form>

</body>
</html>


function performSearch($searchTerm) {
  $data = [
    "Apple",
    "Banana",
    "Orange",
    "Grapefruit"
  ];
  $results = [];
  foreach ($data as $item) {
    if (stripos($item, $searchTerm) !== false) { // Case-insensitive search
      $results[] = $item;
    }
  }
  return $results;
}


<?php

// Assuming you have a database connection established and $db is your database connection object.
//  For simplicity, let's assume you have a database with a table named 'products' with a 'name' column.

// Example Database Connection (Replace with your actual connection details)
// $db = new mysqli("localhost", "username", "password", "database_name");

// Check connection
// if ($db->connect_error) {
//     die("Connection failed: " . $db->connect_error);
// }

// Get search term from the search box (assuming it's named 'search_input')
$search_term = isset($_POST['search_input']) ? $_POST['search_input'] : '';

// Sanitize the search term to prevent SQL injection and XSS
$search_term = htmlspecialchars(trim($search_term)); // Important for security and clean input

// If no search term, don't perform the query
if (empty($search_term)) {
    echo "<p>Please enter a search term.</p>";
} else {

    // Build the SQL query
    $sql = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'";

    // Execute the query
    $result = $db->query($sql);

    // Display the results
    if ($result->num_rows > 0) {
        echo "<table border='1'>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <!-- Add more columns as needed -->
              </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                  <td>" . $row["id"] . "</td>
                  <td>" . $row["name"] . "</td>
                  <td>" . $row["price"] . "</td>
                  <!-- Add more cells based on your database columns -->
                </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No results found for '" . $search_term . "'.</p>";
    }
}

?>
