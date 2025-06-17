
<!DOCTYPE html>
<html>
<head>
    <title>Online Store</title>
    <style>
        .btn {
            background-color: #4CAF50; /* Green */
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<h1>Online Store</h1>

<h2>Products</h2>
<ul>
    <?php
    foreach ($products as $product_id => $product) {
        echo "<li>" . $product['name'] . " - $" . $product['price'] . "  <form method='post'><input type='hidden' name='product_id' value='" . $product_id . "'><input type='number' name='quantity' value='1' min='1'> <button type='submit' class='btn'>Add to Cart</button></form></li>";
    }
    ?>
