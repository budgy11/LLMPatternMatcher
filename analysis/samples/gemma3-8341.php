
        <?php
        // Display cart items
        foreach ($cart as $productId => $quantity) {
            $product = getProductDetails($productId);

            if ($product) {
                echo '<div class="cart-item">';
                echo '<img src="' . $product['image'] . '" alt="' . $product['name'] . '">';
                echo '<h3>' . $product['name'] . '</h3>';
                echo '<p>Quantity: ' . $quantity . '</p>';
                echo '<p>Price: $' . $product['price'] . '</p>';
                echo '<form method="post" action="">';
                echo '<input type="hidden" name="action" value="removeFromCart">';
                echo '<input type="hidden" name="productId" value="' . $productId . '">';
                echo '<button type="submit">Remove</button>';
                echo '</form>';
                echo '</div>';
            }
        }
        ?>
