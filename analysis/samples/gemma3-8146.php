
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
        }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<?php if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else { ?>
