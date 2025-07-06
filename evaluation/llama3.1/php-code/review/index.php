
<?php
// Connect to database
$conn = new mysqli("localhost", "username", "password", "database");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get products from database and render them on the index page
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if (!$result) {
    echo "Error fetching products: " . $conn->error;
} else {
    while ($product = $result->fetch_assoc()) {
        // Create a simple product display using HTML and PHP
        echo "<div class='product'>";
        echo "<h2>" . $product["name"] . "</h2>";
        echo "<p>$" . $product["price"] . "</p>";
        echo "<p>" . $product["description"] . "</p>";
        // Add a link to the product page
        echo "<a href='product.php?id=" . $product["id"] . "'>View Product</a></div>";
    }
}
?>
