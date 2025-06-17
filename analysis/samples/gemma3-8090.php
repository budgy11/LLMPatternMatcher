
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Functionality</title>
</head>
<body>

<h2>Purchase Functionality</h2>

<form method="post" action="">
    <label for="cart_id">Cart ID:</label>
    <input type="text" id="cart_id" name="cart_id" value="<?php echo isset($cart_id) ? $cart_id : ''; ?>">
