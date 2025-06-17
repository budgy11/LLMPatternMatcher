    <label for="cart_id">Cart ID:</label>
    <input type="text" id="cart_id" name="cart_id" required><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <label for="payment_method">Payment Method:</label>
    <select id="payment_method" name="payment_method">
        <option value="credit_card">Credit Card</option>
        <option value="paypal">PayPal</option>
    </select><br><br>

    <input type="submit" value="Place Order">
</form>

</body>
</html>


<?php

// Initialize order data
$order = array();
$total_amount = 0;

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Handle item selection
    $items = isset($_POST["items"]) ? $_POST["items"] : array();
    if (is_array($items)) {
        foreach ($items as $item_id => $quantity) {
            $quantity = intval($quantity); // Ensure quantity is an integer
            if ($quantity > 0) {
                // Simulate item details (replace with actual product data)
                $item_name = "Product " . $item_id;
                $item_price = rand(10, 50); // Random price for demonstration
                $total_item_price = $item_price * $quantity;

                $order[$item_id] = array(
                    "name" => $item_name,
                    "quantity" => $quantity,
                    "price" => $item_price,
                    "total" => $total_item_price
                );
                $total_amount += $total_item_price;
            }
        }
    }
}

?>
