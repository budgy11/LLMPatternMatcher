
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

<h1>Shopping Cart</h1>

<h2>Available Products</h2>
<ul>
    <?php
    foreach ($products as $id => $product) {
        echo "<li>" . $product['name'] . " - $" . $product['price'] . "<br>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='product_id' value='" . $id . "'>";
        echo "<button type='submit' name='add_to_cart'>Add to Cart</button>";
        echo "</form></li>";
    }
    ?>
