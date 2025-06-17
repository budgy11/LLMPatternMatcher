
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<?php
    $cart_contents = getCartContents();

    if (!empty($cart_contents)) {
        echo "<h2>Cart Contents:</h2>";
        foreach ($cart_contents as $product_id => $item) {
            echo "<div class='cart-item'>";
            echo "Product ID: " . $product_id . "<br>";
            echo "Name: " . $products[$product_id]['name'] . "<br>";
            echo "Price: $" . $products[$product_id]['price'] . "<br>";
            echo "Quantity: " . $item['quantity'] . "<br>";
            echo "Subtotal: $" . ($products[$product_id]['price'] * $item['quantity']) . "<br>";
            echo "</div>";
        }

        echo "<p><strong>Total:</strong> $" . round(getTotalCartValue(), 2) . "</p>"; // Calculate and display total
    } else {
        echo "<p>Your cart is empty.</p>";
    }
?>
