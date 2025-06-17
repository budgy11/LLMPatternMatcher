        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required><br><br>

        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" required><br><br>

        <label for="customer_email">Customer Email:</label>
        <input type="email" id="customer_email" name="customer_email" required><br><br>

        <input type="submit" value="Place Order">
    </form>

</body>
</html>


<?php

// Sample product data (replace with your database connection and product data)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    3 => ['id' => 3, 'name' => 'Sneakers', 'price' => 80],
];

// Function to handle the purchase process
function handlePurchase($cart) {
    $total = 0;

    // Calculate the total price
    foreach ($cart as $product_id => $quantity) {
        if (isset($products[$product_id])) {
            $product = $products[$product_id];
            $total += $product['price'] * $quantity;
        } else {
            // Handle invalid product ID (e.g., display an error)
            echo "<p>Error: Product ID " . $product_id . " not found.</p>";
            return false; // Stop the purchase
        }
    }

    // Display the purchase details
    echo "<h2>Purchase Summary</h2>";
    echo "<h3>Items:</h3>";
    foreach ($cart as $product_id => $quantity) {
        echo "<li>" . $products[$product_id]['name'] . " - Quantity: " . $quantity . " - Price: $" . $products[$product_id]['price'] * $quantity . "</li>";
    }
    echo "<p><b>Total: $" . $total . "</b></p>";

    //  In a real application, you would:
    //  1. Process the payment (e.g., using PayPal, Stripe)
    //  2. Send a confirmation email to the customer
    //  3. Update the inventory

    return true; // Purchase complete
}


// Example Usage (Simulating the purchase form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cart = [];

    // Validate and process the cart items
    if (isset($_POST['product1_quantity']) && is_numeric($_POST['product1_quantity']) && $_POST['product1_quantity'] > 0) {
        $cart[] = 1 => $_POST['product1_quantity'];
    }
    if (isset($_POST['product2_quantity']) && is_numeric($_POST['product2_quantity']) && $_POST['product2_quantity'] > 0) {
        $cart[] = 2 => $_POST['product2_quantity'];
    }
    if (isset($_POST['product3_quantity']) && is_numeric($_POST['product3_quantity']) && $_POST['product3_quantity'] > 0) {
        $cart[] = 3 => $_POST['product3_quantity'];
    }


    if (empty($cart)) {
        echo "<p>No items selected.</p>";
    } else {
        handlePurchase($cart);
    }
}
?>
