
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            margin-bottom: 10px;
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<?php if (empty($cart_contents)): ?>
