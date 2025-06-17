
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart - Purchase</title>
</head>
<body>

<h1>Shopping Cart</h1>

<!-- Display Cart Items (Simplified - Replace with a dynamic list) -->
<h2>Cart:</h2>
<?php
if (!empty($cart)) {
    echo "<ul>";
    foreach ($cart as $product_id => $quantity) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Your cart is empty.</p>";
}
?>
