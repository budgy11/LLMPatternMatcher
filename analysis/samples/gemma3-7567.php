

<!DOCTYPE html>
<html>
<head>
    <title>Online Store</title>
</head>
<body>
    <h1>Available Products</h1>
    <ul>
        <?php
        foreach ($products as $id => $product) {
            echo "<li>" . $product['name'] . " - $" . $product['price'] . "<br>";
            echo "<form method='post'>";
            echo "<input type='hidden' name='product_id' value='" . $id . "'>";
            echo "<input type='submit' name='add_to_cart' value='Add to Cart'>";
            echo "</form>";
        }
        ?>
