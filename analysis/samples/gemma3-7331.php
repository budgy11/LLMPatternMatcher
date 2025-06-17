    <select name="product_id">
        <?php
        if (!empty($cart)) {
            foreach ($cart as $item) {
                echo "<option value='" . $item['id'] . "'>" . $item['id'] . " - " . $item['name'] . "</option>";
            }
        } else {
            echo "<option value='1'>T-Shirt</option>";
            echo "<option value='2'>Mug</option>";
            echo "<option value='3'>Book</option>";
        }
        ?>
