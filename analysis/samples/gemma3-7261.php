    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)

// 1. Display the Products
echo "<h1>Available Products</h1>";

// Simulate a product catalog - replace with your actual database query
$products = [
    ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    ['id' => 3, 'name' => 'Hat', 'price' => 15],
];

foreach ($products as $product) {
    echo "<p><strong>$product[name]</strong> - $product[price] </p>";
}

// 2.  Handle the Purchase Form (POST Request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input (basic example - enhance for real applications)
    $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1; // Default quantity is 1

    if ($productId && $quantity > 0) {
        // 3. Find the product by ID
        $product = null;
        foreach ($products as $p) {
            if ($p['id'] == $productId) {
                $product = $p;
                break;
            }
        }

        if ($product) {
            // 4. Calculate the total price
            $totalPrice = $product['price'] * $quantity;

            // 5.  Store the order information (Simplified - Use a database for persistence)
            // In a real application, you'd store this in a database.
            echo "<h2>Order Confirmation</h2>";
            echo "<p>Product: " . $product['name'] . "</p>";
            echo "<p>Quantity: " . $quantity . "</p>";
            echo "<p>Total Price: $" . $totalPrice . "</p>";
            echo "<p>Thank you for your order!</p>";

            // Optional:  Session to track order
            session_start();
            $_SESSION['order_id'] = uniqid();  // Generate a unique order ID
            $_SESSION['product_id'] = $productId;
            $_SESSION['quantity'] = $quantity;
            $_SESSION['total_price'] = $totalPrice;
        } else {
            echo "<p>Product not found.</p>";
        }
    } else {
        echo "<p>Invalid input. Please select a product and enter a valid quantity.</p>";
    }
}

?>
