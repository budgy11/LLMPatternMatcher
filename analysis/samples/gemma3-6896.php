
  <hr>

  <h2>Add to Cart</h2>
  <form method="post">
    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required>
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="1" min="1">
    <input type="submit" name="add_to_cart" value="Add to Cart">
  </form>

</body>
</html>


<?php

// Data Storage (Replace with a database in a real application)
$shopping_cart = [];
$item_prices = [
    'apple' => 1.00,
    'banana' => 0.50,
    'orange' => 0.75,
    'grapefruit' => 2.00
];

// Function to add an item to the cart
function addToCart($item, $quantity = 1) {
    if (isset($item_prices[$item])) {
        $item_price = $item_prices[$item];
        $shopping_cart[$item]['quantity'] = $shopping_cart[$item]['quantity'] + $quantity;
        $subtotal = $item_price * $shopping_cart[$item]['quantity'] + $item_price * $quantity;
        // You'd typically update a total price here as well
    } else {
        echo "Error: Item '$item' not found.";
    }
}

// Function to display the shopping cart
function displayCart() {
    echo "<h2>Shopping Cart</h2>";
    if (empty($shopping_cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($shopping_cart as $item => $details) {
        $quantity = $details['quantity'];
        $item_price = $item_prices[$item];
        $total = $item_price * $quantity;
        echo "<li>$item (Quantity: $quantity) - Price: $item_price - Total: $total</li>";
    }
    echo "</ul>";

    // Calculate total cart cost
    $totalCartCost = 0;
    foreach ($shopping_cart as $item => $details) {
        $totalCartCost += $item_prices[$item] * $details['quantity'];
    }

    echo "<p><strong>Total Cart Cost: $" . number_format($totalCartCost, 2) . "</strong></p>";
}

// Function to handle the purchase (simplified example)
function handlePurchase() {
    if (empty($shopping_cart)) {
        echo "<p>Your cart is empty.  Nothing to purchase!</p>";
        return;
    }

    //  In a real application, you'd process payment, update inventory, etc.
    echo "<p>Order placed successfully!  (Simulated Payment)</p>";

    // Clear the cart after purchase (optional)
    $shopping_cart = [];

    // Redirect to a confirmation page, or display a message.
    displayCart();  // Display the cart one last time after the purchase.
}


// --- Example Usage (Interactive Part) ---

// Add items to the cart
$action = $_GET['action'] ?? $_POST['action'] ?? '';  // Get action from GET or POST

if ($action === 'add') {
    $item = $_POST['item'];
    $quantity = (int)$_POST['quantity'] ?? 1; // Ensure quantity is an integer
    if($item && $quantity > 0){
        addToCart($item, $quantity);
    }
}

// Handle the purchase
if ($action === 'purchase') {
    handlePurchase();
}

// Display the cart
displayCart();

?>
