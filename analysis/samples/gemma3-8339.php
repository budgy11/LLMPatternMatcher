
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            border: 1px solid #ccc;
            margin-bottom: 10px;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .cart-item img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }
    </style>
</head>
<body>

    <h1>Shopping Cart</h1>

    <?php if (empty($cart)): ?>
