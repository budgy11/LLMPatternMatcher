```php
<?php
require_once 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"] ?? 1; // Default to 1 if no quantity is provided

    if (get_product_by_id($conn, $product_id)) {
        $cart_id = insert_cart_item($conn, 1, $product_id, $quantity); // Using user_id 1 for simplicity.

        echo "<p>Item added to cart.  Cart ID: " . $cart_id . "</p>";
        // Redirect to cart.php
        header("Location: cart.php");
        exit();
    } else {
        echo "<p>Product not found.</p>";
    }
}
?>
```