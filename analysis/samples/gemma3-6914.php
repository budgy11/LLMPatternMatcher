
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart Purchase</title>
</head>
<body>

<h1>Shopping Cart</h1>

<form method="post" action="">
    <label for="cart_items">Cart Items (JSON Array):</label><br>
    <textarea id="cart_items" name="cart_items" rows="10" cols="50">
    [
        { "product_id": 1, "quantity": 2 },
        { "product_id": 2, "quantity": 1 }
    ]
</textarea><br><br>

    <label for="shipping_address">Shipping Address:</label><br>
    <input type="text" id="shipping_address" name="shipping_address" value="<?php if (isset($_POST['shipping_address'])) echo htmlspecialchars($_POST['shipping_address']); ?>"><br><br>
