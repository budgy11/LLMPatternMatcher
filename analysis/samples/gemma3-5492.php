

<?php
// Assuming $db is your established database connection
// This script will display details for a specific product

// Get the product ID from the URL
$productId = isset($_GET['id']) ? $_GET['id'] : '';

if (!empty($productId)) {
    $query = "SELECT * FROM products WHERE id = " . $productId;
    $result = mysqli_query($db, $query);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $product = mysqli_fetch_assoc($result);
            echo "<h3>Product Details: " . $product['name'] . "</h3>";
            echo "<img src='images/" . $product['image'] . "' alt='" . $product['name'] . "'><br>";  // Assuming you have images in an 'images' folder
            echo "<p>" . $product['description'] . "</p>";
            echo "<p>Price: $" . $product['price'] . "</p>";
        } else {
            echo "<p>Product not found.</p>";
        }
        mysqli_free_result($result);
    } else {
        echo "<p>Error executing the query.</p>";
        echo mysqli_error($db);
    }
}
?>
