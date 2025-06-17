
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

    <h1>Online Store</h1>

    <h2>Products</h2>
    <ul>
        <?php
        foreach ($products as $product_id => $product_data) {
            echo "<li>" . $product_data['name'] . " - $" . $product_data['price'] . "<br>";
            echo "<form method='post'>";
            echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
            echo "<input type='number' name='quantity' value='1' min='1'>";
            echo "<button type='submit' name='add_to_cart'>Add to Cart</button>";
            echo "</form></li>";
        }
        ?>
