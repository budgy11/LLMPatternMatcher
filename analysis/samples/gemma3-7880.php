
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body { font-family: sans-serif; }
        .cart-item { border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<?php if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else { ?>
