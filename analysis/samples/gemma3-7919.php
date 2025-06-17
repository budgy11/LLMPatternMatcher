            <h2>Items in Cart</h2>
            <?php
            foreach ($_SESSION['cart'] as $product_id => $item) {
                echo '<div class="cart-item">';
                echo '<strong>' . $products[$product_id]['name'] . '</strong> - $' . $products[$product_id]['price'] . ' ';
                echo 'Quantity: ' . $item['quantity'];
                echo '</div>';
            }
            ?>
