```php
<?php
// cart.php

require_once 'db_connect.php';

// Initialize cart (if empty)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


// Add item to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    add_to_cart($product_id, $quantity);
}

// Remove item from cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    remove_from_cart($product_id);
}

// Update quantity in cart
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    update_quantity($product_id, $new_quantity);
}

// Display cart contents
echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product = get_product_by_id($product_id); // Function to retrieve product details

        echo "<li>";
        echo "<h3>" . $product['product_name'] . "</h3>";
        echo "<p>Quantity: " . $quantity . "</p>";
        echo "<p>Price: $" . $product['price'] . "</p>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
        echo "<input type='hidden' name='quantity' value='" . $quantity . "'>";
        echo "<input type='submit' value='Remove from Cart'>";
        echo "</form>";
        echo "</li>";
    }
    echo "</ul>";
}

$conn->close();
?>
```