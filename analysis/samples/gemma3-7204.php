
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

    <h1>Online Store</h1>

    <h2>Product List</h2>
    <ul>
        <?php
        foreach ($product_list as $product) {
            echo "<li>" . $product['name'] . " - $" . number_format($product['price'], 2) . " <a href='cart.php?action=add&product_id=" . $product['id'] . "'>Add to Cart</a></li>";
        }
        ?>
