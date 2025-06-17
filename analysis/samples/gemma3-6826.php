    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <label for="shipping_address">Shipping Address:</label>
    <input type="text" id="shipping_address" name="shipping_address" required><br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php
session_start();

// Sample product data (replace with your actual product details)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15]
];

// Check if the user is logged in (simplistic check - replace with a proper authentication system)
if (!isset($_SESSION['user_id'])) {
    echo "<p>You must be logged in to place an order.</p>";
} else {
    echo "<h2>Available Products</h2>";
    echo "<ul>";
    foreach ($products as $id => $product) {
        echo "<li>" . $product['name'] . " - $" . $product['price'] . "</li>";
    }
    echo "</ul>";

    // Display the order form
    echo "<h2>Place an Order</h2>";
    echo "<form action='order.php' method='post'>";
    echo "Product ID: ";
    echo "<select name='product_id'>";
    foreach ($products as $id => $product) {
        echo "<option value='" . $id . "'>" . $product['name'] . "</option>";
    }
    echo "</select><br>";

    echo "Quantity: ";
    echo "<input type='number' name='quantity' value='1' min='1'><br>";

    echo "<input type='submit' value='Place Order'>";
    echo "</form>";
}
?>
