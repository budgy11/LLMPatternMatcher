

<?php
// add_to_cart.php

require_once 'cart.php';

$product_id = $_GET['product_id'];
$quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1; // Default to 1 if not provided.

if (empty($cart)) {
    $cart[$product_id] = $quantity;
} else {
    if (isset($cart[$product_id])) {
        $cart[$product_id] += $quantity;
    } else {
        $cart[$product_id] = $quantity;
    }
}

writeCart($cart); // Save the updated cart data
header("Location: index.php"); // Redirect back to the main page.
exit();
?>
