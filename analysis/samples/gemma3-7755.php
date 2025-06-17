        <ul>
            <?php
            foreach ($cart as $product_id => $quantity) {
                echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
            }
            ?>
