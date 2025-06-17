
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

    <h1>Shopping Cart</h1>

    <form method="post" action="">
        <label for="product_id">Product ID:</label>
        <select name="product_id" id="product_id">
            <?php
            // Assuming you have a database or array to get product IDs.
            // This is just a placeholder.  Replace with your actual product data.
            $products = array(
                1 => 'Product A',
                2 => 'Product B',
                3 => 'Product C'
            );
            foreach ($products as $id => $name) {
                echo "<option value='" . $id . "'>" . $name . "</option>";
            }
            ?>
