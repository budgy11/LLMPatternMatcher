
<!DOCTYPE html>
<html>
<head>
    <title>Simple Shopping Cart</title>
    <style>
        .remove-button {
            background-color: #f44336;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h2>Products</h2>
<form method="post">
    <label for="product_name">Product Name:</label>
    <select name="product_name" id="product_name">
        <?php
        foreach (array_keys($items) as $product_name) {
            echo "<option value='" . $product_name . "'>" . $product_name . "</option>";
        }
        ?>
