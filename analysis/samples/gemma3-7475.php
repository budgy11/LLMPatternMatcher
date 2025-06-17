
<!DOCTYPE html>
<html>
<head>
    <title>Simple Purchase Functionality</title>
</head>
<body>

    <h1>Shop</h1>

    <h2>Products</h2>
    <ul>
        <?php
        foreach ($products as $id => $product) {
            echo "<li>" . $product['name'] . " - $" . $product['price'] . " <a href='?action=add&product_id=" . $id . "'>Add to Cart</a></li>";
        }
        ?>
