
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

    <h1>Shopping Cart</h1>

    <form method="post">
        <label for="product_id">Select Product:</label>
        <select name="product_id" id="product_id">
            <?php
            foreach ($products as $id => $product) {
                echo '<option value="' . $id . '">' . $product['name'] . '</option>';
            }
            ?>
