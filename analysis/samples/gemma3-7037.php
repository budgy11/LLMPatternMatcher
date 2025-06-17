
  <form method="POST" action="purchase.php">
    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required>
    <br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required>
    <br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required>
    <br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required>
    <br><br>

    <input type="submit" value="Purchase">
  </form>

  <br>
  <a href="index.php">Back to Product Listing</a> <!-- Example link back to your product list -->

</body>
</html>


<?php

// Configuration (Replace with your actual values)
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75],
    4 => ['name' => 'Monitor', 'price' => 300],
];

$cart = []; // Array to store items in the cart

// Function to add an item to the cart
function addToCart($product_id, $quantity = 1)
{
    if (array_key_exists($product_id, $products)) {
        $product = $products[$product_id];

        // Check if the item is already in the cart
        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += $quantity;
        } else {
            $cart[$product_id] = ['name' => $product['name'], 'price' => $product['price'], 'quantity' => $quantity];
        }
    } else {
        echo "Product ID $product_id not found.";
    }
}

// Function to display the cart
function displayCart()
{
    echo "<h2>Shopping Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($cart as $item_id => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";

    // Calculate total cost
    $total = 0;
    foreach ($cart as $item_id => $item) {
        $total += $item['price'] * $item['quantity'];
    }
    echo "<p><strong>Total: $" . $total . "</strong></p>";
}


// Example Usage (Simulating a purchase)
// Add items to the cart
addToCart(1, 2); // Add 2 Laptops
addToCart(2, 3); // Add 3 Mice
addToCart(3); // Add 1 Keyboard (default quantity of 1)

// Display the cart
displayCart();

//  ---  Simulating a Purchase / Checkout (Illustrative) ---
//  In a real application, you'd process this securely.
//  This is a simplified example.

//  To make a purchase, you'd typically:
//  1. Collect payment information (using a secure payment gateway).
//  2. Verify the payment.
//  3. Update inventory (decrease stock levels).
//  4. Generate an order confirmation.
//  5. Store the order details.

echo "<p>Thank you for your purchase!</p>";



?>
