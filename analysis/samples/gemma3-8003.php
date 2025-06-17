    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <label for="payment_amount">Payment Amount:</label>
    <input type="number" id="payment_amount" name="payment_amount" step="0.01" required><br><br>

    <input type="submit" value="Place Order">
</form>

<?php

//  Example Cart Data (for testing)
//  You would likely get this from a shopping cart system in a real application.
//  This is just to demonstrate the functionality.
$_SESSION['cart'] = [
    ['product_id' => 1, 'quantity' => 2],
    ['product_id' => 2, 'quantity' => 1],
];

?>
