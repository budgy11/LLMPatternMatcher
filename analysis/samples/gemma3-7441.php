        <br><br>

        <label for="payment_method">Payment Method:</label>
        <select id="payment_method" name="payment_method">
            <option value="credit_card">Credit Card</option>
            <option value="paypal">PayPal</option>
            <option value="other">Other</option>
        </select>
        <br><br>


        <h2>Items in Cart:</h2>
        <?php
        //Display cart items
        if (!empty($cart['items'])) {
            echo "<ul>";
            foreach ($cart['items'] as $product_id => $quantity) {
                //Get product name (replace with your database lookup)
                $product_name = "Product " . $product_id; // Replace this with actual product name retrieval
                echo "<li>$product_name x $quantity</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Your cart is empty.</p>";
        }
        ?>
