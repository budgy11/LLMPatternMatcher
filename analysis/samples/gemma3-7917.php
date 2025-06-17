
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body { font-family: sans-serif; }
        .cart { border: 1px solid #ccc; padding: 10px; margin: 10px; }
        .cart-item { margin-bottom: 5px; }
        .cart-total { font-weight: bold; }
    </style>
</head>
<body>

    <h1>Shopping Cart</h1>

    <div class="cart">
        <?php if (empty($_SESSION['cart'])) { ?>
