    <label for="product_id">Select Product:</label>
    <select name="product_id" id="product_id">
        <?php
        foreach ($products as $product) {
            echo "<option value=\"$product[id]\" >$product[name]</option>";
        }
        ?>
