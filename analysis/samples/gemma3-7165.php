
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <h1>Shopping Cart</h1>

    <?php if (!empty($cart_items)) { ?>
