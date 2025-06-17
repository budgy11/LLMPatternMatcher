
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart {
            border: 1px solid #ccc;
            padding: 10px;
            margin-top: 20px;
        }
        .cart table {
            width: 100%;
            border-collapse: collapse;
        }
        .cart th, .cart td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>

    <h1>Shopping Cart</h1>

    <form method="post" action="">
        <h2>Add Products to Cart</h2>
        <?php
        foreach ($products as $id => $product) {
            echo "<div class='product'>";
            echo "<label for='product_" . $id . "'>" . $product['name'] . " - $" . $product['price'] . "</label><br>";
            echo "<input type='number' id='product_" . $id . "' name='product_id' value='' min='1' max='10' style='width:50px;'>"; // Limit quantity (optional)
            echo "<input type='submit' value='Add to Cart' name='action' value='add_to_cart'>";
            echo "</div>";
        }
        ?>
