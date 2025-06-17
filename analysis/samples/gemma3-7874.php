
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            margin-bottom: 10px;
            border: 1px solid #ccc;
            padding: 10px;
        }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<?php
// Display cart items
if (count($_SESSION['cart']) > 0) {
    echo "<h2>Cart Items:</h2>";
    foreach ($_SESSION['cart'] as $item) {
        echo '<div class="cart-item">';
        echo '<strong>' . $item['name'] . '</strong> - $' . $item['price'] . '<br>';
        echo 'Quantity: ' . $item['quantity'] . '<br>';
        echo '<form method="post" action="">';
        echo '<input type="hidden" name="product_id" value="' . $item['id'] . '">';
        echo '<input type="hidden" name="update_quantity" value="update">';
        echo '<input type="number" name="quantity" value="' . $item['quantity'] . '" min="1" style="width: 50px;">';
        echo '<button type="submit">Update Quantity</button>';
        echo '</form>';
        echo '<form method="post" action="">';
        echo '<input type="hidden" name="product_id" value="' . $item['id'] . '">';
        echo '<input type="hidden" name="remove_product" value="remove">';
        echo '<button type="submit">Remove</button>';
        echo '</form>';
        echo '<br>';
    }
} else {
    echo '<p>Your cart is empty.</p>';
}
?>
