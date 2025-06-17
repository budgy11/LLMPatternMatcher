
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body { font-family: sans-serif; }
        .cart-item { border: 1px solid #ccc; margin: 10px; padding: 10px; }
        .cart-total { font-weight: bold; }
    </style>
</head>
<body>

    <h1>Shopping Cart</h1>

    <form method="post" action="">
        <h2>Items in Cart:</h2>
        <?php if (empty($cart_items)): ?>
