
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Functionality</title>
</head>
<body>

<h1>Purchase Products</h1>

<form method="post" action="">
    <label for="product_id">Product ID:</label>
    <select name="product_id" id="product_id">
        <?php
        foreach ($products as $id => $product) {
            echo "<option value=\"" . $id . "\">" . $product['name'] . "</option>";
        }
        ?>
