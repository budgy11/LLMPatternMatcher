

<?php

// Assuming you have a database connection established (e.g., using mysqli)
// and you have a table named 'products' with a column named 'name'
// This example uses mysqli for database interaction. Replace with your own database connection details.

// Database connection details - Replace with your actual values
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the search term is provided
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];

    // Sanitize the search term (important for security)
    $searchTerm = $conn->real_escape_string($searchTerm);

    // Build the SQL query
    $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

    // Execute the query
    $result = $conn->query($sql);

    // Display the search results
    if ($result->num_rows > 0) {
        echo "<h2>Search Results for: " . $searchTerm . "</h2>";
        echo "<form action="" method = "post">";
        echo "<input type = 'text' name = 'search' value = 'Search...'";
        echo ">";
        echo "<input type = 'submit' value = 'Search'>";
        echo "</form>";

        echo "<table border='1'>";
        echo "<tr><th>Name</th><th>Description</th><th>Price</th></tr>"; // Adjust column headers as needed

        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["description"] . "</td>";
            echo "<td>" . $row["price"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";

    } else {
        echo "<p>No products found matching '" . $searchTerm . "'.</p>";
    }
} else {
    // Display the search bar initially
    echo "<h2>Search Products</h2>";
    echo "<form action="" method = "post">";
    echo "<input type = 'text' name = 'search' value = 'Search...'";
    echo ">";
    echo "<input type = 'submit' value = 'Search'>";
    echo "</form>";
}

$conn->close();
?>
