
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

<h1>Shopping Cart</h1>

<form method="post">
    <h2>Add Items to Cart</h2>
    <?php
    foreach (array_keys($products) as $productId) {
        echo '<label for="product_id_' . $productId . '">Add ' . $products[$productId]['name'] . ':</label>';
        echo '<input type="number" id="product_id_' . $productId . '" name="product_id" value="0" min="0">';
        echo '<input type="submit" name="add_to_cart" value="Add to Cart">';
    }
    ?>
