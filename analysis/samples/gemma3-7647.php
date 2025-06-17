
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: sans-serif;
        }
        .cart-item {
            margin-bottom: 10px;
            border: 1px solid #ccc;
            padding: 10px;
        }
        .cart-item img {
            max-width: 100px;
            height: auto;
            float: left;
            margin-right: 10px;
        }
        .cart-item p {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

    <h1>Shopping Cart</h1>

    <?php if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
    } else { ?>
