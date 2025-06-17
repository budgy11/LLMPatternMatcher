
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body { font-family: sans-serif; }
        .cart-item { border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; }
        .cart-total { font-weight: bold; }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<?php
    $cart = getCart();

    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<h2>Items in Cart:</h2>";
        foreach ($cart as $item) {
            echo "<div class='cart-item'>";
            echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . "<br>";
            echo "Quantity: " . $item['quantity'] . "<br>";
            echo "Total: $" . $item['total_price'] . "<br>";
            echo "<form method='post'>";
            echo "<input type='hidden' name='product_id' value='" . $item['product_id'] . "'>";
            echo "<input type='number' name='quantity' value='" . $item['quantity'] . "' min='1' max='10'>";  //Limit quantities
            echo "<input type='submit' value='Update'>";
            echo "</form>";
            echo "</div>";
        }

        echo "<div class='cart-total'>Total Cart Value: $" . calculateTotalCartValue() . "</div>";
    }
?>
