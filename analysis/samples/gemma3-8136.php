    <form method="post">
        <label for="clear_cart">Clear Cart:</label>
        <button type="submit" name="clear_cart">Clear</button>
    </form>

    <h2>Cart Items:</h2>
    <?php
    $total = 0;
    foreach ($cart_contents as $item_id => $item_data):
        $product_name = $item_data['name'];
        $product_price = $item_data['price'];
        $quantity = $item_data['quantity'];
        $item_total = $product_price * $quantity;

        $total += $item_total;

        echo "<h3>" . $product_name . "</h3>";
        echo "<p>Quantity: " . $quantity . "</p>";
        echo "<p>Price per item: $" . $product_price . "</p>";
        echo "<p>Item Total: $" . $item_total . "</p>";
        echo "<input type='number' name='quantity[{$item_id}]' value='{$quantity}' min='1' />"; // Use this for updating quantity
        echo "<br>";
    endforeach;
    ?>
