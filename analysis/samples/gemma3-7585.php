
<!DOCTYPE html>
<html>
<head>
    <title>Ecommerce Purchase</title>
</head>
<body>

<h1>Ecommerce Purchase</h1>

<h2>Select Product and Quantity:</h2>

<form method="post">
    <label for="product_id">Product ID:</label>
    <select name="product_id" id="product_id">
        <?php
        foreach ($products as $id => $product) {
            echo "<option value=\"" . $id . "\">" . $id . " - " . $product['name'] . "</option>";
        }
        ?>
