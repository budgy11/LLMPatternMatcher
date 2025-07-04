<?php
// purchase.php

require_once 'db_connect.php';
session_start(); // Start session management

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    echo "<h2>Your cart is empty.  Please add items before proceeding.</h2>";
    exit; // Stop execution
}

// Get cart items
$cart_items = $_SESSION['cart'];

// Calculate total
$total = 0;
foreach ($cart_items as $product_id => $quantity) {
    $product = get_product_by_id($product_id); // Retrieve product details
    $total += $product['price'] * $quantity;
}

// Simulate order processing (replace with actual order handling)
echo "<h2>Order Confirmation</h2>";
echo "<p>Thank you for your order!</p>";
echo "<p>Items Ordered:</p>";
foreach ($cart_items as $product_id => $quantity) {
    $product = get_product_by_id($product_id);
    echo "<p>" . $product['product_name'] . " - Quantity: " . $quantity . " - Price: $" . $product['price'] . "</p>";
}
echo "<p>Total: $" . $total . "</p>";

// Clear the cart after successful order
$_SESSION['cart'] = array();

echo "<a href='index.php'>Continue Shopping</a>";

$conn->close();
?>
