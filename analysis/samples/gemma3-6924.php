
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .product {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px;
            width: 200px;
        }
    </style>
</head>
<body>

    <h1>Online Store</h1>

    <div class="product">
        <h2>T-Shirt</h2>
        <p>Price: $20.00</p>
        <form method="post">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="1" min="1">
            <button type="submit" name="update_quantity" value="<?php echo $product_id = 1; ?>">Add to Cart</button>
