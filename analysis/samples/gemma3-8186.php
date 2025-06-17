
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            margin-bottom: 10px;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .cart-item.removed {
            border: 1px solid red;
            padding: 10px;
        }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<?php
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<div class='cart-items'>";
    $cart_total = getCartTotal();
    echo "<strong>Total: $" . number_format($cart_total, 2) . "</strong>";
    echo "</div>";

    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        if (isset($product_data[$product_id])) {
            echo "<div class='cart-item'>";
            echo "<h3>" . $product_data[$product_id]['name'] . "</h3>";
            echo "<p>Quantity: " . $quantity . "</p>";
            echo "<p>Price: $" . number_format($product_data[$product_id]['price'], 2) . "</p>";
            echo "<form method='post'>";
            echo "<label for='quantity_" . $product_id . "'>Quantity:</label>";
            echo "<input type='number' id='quantity_" . $product_id . "' value='" . $quantity . "' min='1' max='" . $product_data[$product_id]['stock'] . "' name='quantity_" . $product_id . "'>";
            echo "<button type='submit' name='update_cart'>" . (empty($product_data[$product_id]['stock']) ? "Remove" : "Update") . "</button>";
            echo "</form>";
            echo "<br>";
        }
    }
    echo "</div>";
}
?>
