
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        /* Basic styling - improve this! */
        body { font-family: sans-serif; }
        .cart-item { border: 1px solid #ccc; padding: 10px; margin: 10px; }
        .cart-total { text-align: right; font-weight: bold; }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<?php
// Display cart contents
$cart_contents = getCartContents();

if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<h2>Cart Items</h2>";
    foreach ($cart_contents as $product_id => $item) {
        echo '<div class="cart-item">
                <strong>Product Name:</strong> ' . $item['name'] . '<br>
                <strong>Price:</strong> $' . $item['price'] . '<br>
                <strong>Quantity:</strong> ' . $item['quantity'] . '<br>
                <form method="post" action="cart.php">
                    <input type="hidden" name="product_id" value="' . $product_id . '">
                    <input type="submit" value="Update Quantity">
                </form>
                <form method="post" action="cart.php">
                    <input type="hidden" name="product_id" value="' . $product_id . '">
                    <input type="submit" value="Remove from Cart">
                </form>
            </div>';
    }

    // Calculate and display the total
    $total = calculateTotal();
    echo '<div class="cart-total"><strong>Total:</strong> $' . $total . '</div>';
}
?>
