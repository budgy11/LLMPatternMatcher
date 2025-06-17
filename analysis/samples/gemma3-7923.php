                    <?php
                    foreach ($_SESSION['cart'] as $product_id => $item) {
                        echo '<option value="' . $product_id . '">' . $products[$product_id]['name'] . '</option>';
                    }
                    ?>
