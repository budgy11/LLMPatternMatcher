
<!-- HTML Form to Add Items to Cart (Example) -->
<form method="post">
    <label for="product_id">Product ID:</label>
    <select name="product_id" id="product_id">
        <?php
        foreach ($products as $id => $product) {
            echo "<option value=\"$id\">$id - $product['name']</option>";
        }
        ?>
