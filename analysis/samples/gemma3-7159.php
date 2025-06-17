        <label for="product_id">Product ID:</label>
        <select name="product_id" id="product_id">
            <?php
            foreach ($products as $product) {
                echo "<option value='" . $product['id'] . "'>" . $product['id'] . " - " . $product['name'] . "</option>";
            }
            ?>
