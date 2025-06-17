
<!DOCTYPE html>
<html>
<head>
    <title>Search Products</title>
</head>
<body>

    <h1>Search Products</h1>

    <form method="post" action="">
        <input type="text" name="search_input" placeholder="Enter search term">
        <button type="submit">Search</button>
    </form>

</body>
</html>


<?php
// ... (Database connection and setup as before)

// Get search term from the search box
$search_term = isset($_POST['search_input']) ? $_POST['search_input'] : '';

if (empty($search_term)) {
    echo "<p>Please enter a search term.</p>";
} else {

    // Build the SQL query with placeholders
    $sql = "SELECT * FROM products WHERE name LIKE ?"; // Use '?' as a placeholder

    // Prepare the statement
    $stmt = $db->prepare($sql);

    // Bind the parameter (user input)
    $stmt->bind_param("s", $search_term);  // "s" indicates a string

    // Execute the query
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    // Display the results
    if ($result->num_rows > 0) {
        echo "<table border='1'>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
              </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                  <td>" . $row["id"] . "</td>
                  <td>" . $row["name"] . "</td>
                  <td>" . $row["price"] . "</td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No results found for '" . $search_term . "'.</p>";
    }

    // Close the statement
    $stmt->close();
}

?>
