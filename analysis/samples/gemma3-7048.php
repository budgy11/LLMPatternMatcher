
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            border: 1px solid #ccc;
            margin-bottom: 10px;
            padding: 10px;
        }
    </style>
</head>
<body>

    <h1>Shopping Cart</h1>

    <form method="post">
        <?php if (empty($cart)) {
            echo "<p>Your cart is empty.</p>";
        } else { ?>
