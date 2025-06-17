
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
        .cart-total {
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<?php if (empty($cart_items)): ?>
