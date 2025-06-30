```php
<?php
// product.php

require_once 'db_connect.php';

// Get product ID from URL
$id = $_GET['id'];

// SQL query
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error);
}

if ($result->num_rows == 0) {
    echo "Product not found.";
} else {
    $product = $result->fetch_assoc();

    echo "<h2>" . $product['product_name'] . "</h2>";
    echo "<img src='" . $product['image_url'] . "' width='200'><br><br>";
    echo "<p>" . $product['description'] . "</p>";
    echo "<p>Price: $" . $product['price'] . "</p>";

    // Add to cart functionality (simplified)
    if (isset($_POST['add_to_cart'])) {
        add_to_cart($product); // Call the function (defined in purchase.php)
    }
}

$conn->close();
?>
```