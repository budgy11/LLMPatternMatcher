    <label for="product_id">Select Product:</label>
    <select name="product_id" id="product_id">
        <?php
        foreach ($products as $id => $product) {
            echo "<option value=\"$id\">" . $product['name'] . "</option>";
        }
        ?>
