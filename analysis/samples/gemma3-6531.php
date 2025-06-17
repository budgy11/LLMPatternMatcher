
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Shopping Cart Example</h1>

    <!-- Buttons to add items to the cart -->
    <h2>Add Items</h2>
    <?php
    foreach ($products as $id => $product) {
        echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";
        echo "<input type='hidden' name='product_id' value='" . $id . "'>";
        echo "<button type='submit' name='add_to_cart' value='" . $id . "'>" . $product['name'] . " - $" . $product['price'] . "</button>";
        echo "</form>";
    }
    ?>
