
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Functionality</title>
</head>
<body>

<h1>Purchase Functionality</h1>

<form method="post" action="">
    <label for="cart_id">Cart ID:</label>
    <input type="text" id="cart_id" name="cart_id" value="<?php echo isset($_POST['cart_id']) ? htmlspecialchars($_POST['cart_id']) : ''; ?>">
