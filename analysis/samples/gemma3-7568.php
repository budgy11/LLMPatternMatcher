    </ul>
    <br>
    <a href='cart.php'>View Cart</a>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>

    <?php
    $cartItems = getCartItems();

    if (empty($cartItems)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<h2>Items in Cart:</h2>";
        foreach ($cartItems as $item) {
            echo $products[$item]['name'] . " - $" . $products[$item]['price'] . "<br>";
        }

        $total = calculateTotal();
        echo "<p>Total: $" . $total . "</p>";
        echo "<form method='post'>
                    <input type='hidden' name='submit_order' value='true'>
                    <input type='submit' name='submit_order' value='Place Order'>";
    }
    ?>
