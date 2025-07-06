<?php

$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_ecommerce_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to execute queries
function executeQuery($conn, $sql) {
    $result = $conn->query($sql);
    if (!$result) {
        echo "Error executing query: " . $conn->error;
        return false;
    }
    return $result;
}

?>
<?php
require_once 'database.php'; // Include the database connection

$sql = "SELECT * FROM products";
$result = executeQuery($conn, $sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["product_id"] . "</td>";
        echo "<td>" . $row["product_name"] . "</td>";
        echo "<td>" . $row["category"] . "</td>";
        echo "<td>" . $row["price"] . "</td>";
        echo "</tr>";
    }
} else {
    echo "No products found.";
}

$conn->close();
?>
<?php
require_once 'database.php';
require_once 'products.php'; //Include products.php

// Handle Search Query
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];

    $sql = "SELECT * FROM products WHERE product_name LIKE '%" . $searchTerm . "%' OR category LIKE '%" . $searchTerm . "%'";
    $result = executeQuery($conn, $sql);

    if ($result->num_rows > 0) {
        // Display Search Results
        echo "<h2>Search Results for: " . $searchTerm . "</h2>";
        //  Display search results here - similar logic as in products.php
    } else {
        echo "<p>No products found matching your search.</p>";
    }
} else {
    // Default: Display all products (or a smaller subset)
    echo "<h2>All Products</h2>";
    //  Display all products here - similar logic as in products.php
}

$conn->close();
?>

<form action="index.php" method="get">
    <input type="text" name="search" placeholder="Search products...">
    <button type="submit">Search</button>
</form>
    // Example using prepared statements (stronger security)
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_name LIKE ? OR category LIKE ?");
    $searchTerm = "%" . $searchTerm . "%"; // Escape properly
    $stmt->bind_param("s", $searchTerm, $searchTerm); // "s" for string
    $stmt->execute();
    //  Fetch results as usual
    ```