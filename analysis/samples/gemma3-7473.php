
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        /* Basic styling - improve as needed */
        body { font-family: sans-serif; }
        ul { list-style: none; padding: 0; }
        li { margin-bottom: 10px; }
    </style>
</head>
<body>

    <h1>Shopping Cart</h1>

    <form method="post" action="cart.php">
        <label for="product_id">Product ID:</label>
        <select name="product_id" id="product_id">
            <?php
            foreach ($products as $id => $product) {
                echo "<option value='$id'>$id</option>";
            }
            ?>
