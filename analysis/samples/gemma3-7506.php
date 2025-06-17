
<!DOCTYPE html>
<html>
<head>
    <title>Simple Purchase Functionality</title>
</head>
<body>

    <h1>Shopping Cart</h1>

    <form method="post" action="">
        <label for="product_id">Product ID:</label>
        <select name="product_id" id="product_id">
            <?php
            foreach ($products as $id => $product) {
                echo "<option value=\"" . $id . "\">" . $id . " - " . $product['name'] . "</option>";
            }
            ?>
