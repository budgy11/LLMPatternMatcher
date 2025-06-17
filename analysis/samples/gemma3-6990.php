    <br><br>

    <button type="submit">Add to Cart</button>
  </form>

  <br>
  <h3>Cart (Currently Empty)</h3>

</body>
</html>


<?php

// Configuration (Adjust these as needed)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20],
    2 => ['name' => 'Jeans', 'price' => 50],
    3 => ['name' => 'Hat', 'price' => 15],
];

$cart = []; // Array to store items in the cart
$order_id = 0;

// Function to add an item to the cart
function addToCart($productId, $quantity = 1, $cart = &$cart) {
    if (isset($products[$productId])) {
        $product = $products[$productId];
        $newItem = ['product_id' => $productId, 'name' => $product['name'], 'price' => $product['price'], 'quantity' => $quantity];

        // Check if item already in cart - add to quantity
        if (in_array($productId, array_column($cart, 'product_id'))) {
            foreach ($cart as &$item) { // Use reference to modify the array in place
                if ($item['product_id'] == $productId) {
                    $item['quantity'] += $quantity;
                    break;
                }
            }
        } else {
            $cart[] = $newItem;
        }
    } else {
        echo "Product ID $productId not found.";
    }
}

// Function to calculate the total cart value
function calculateTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Function to display the cart
function displayCart() {
    echo "<h2>Shopping Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>" . $item['name'] . " - $" . number_format($item['price'], 2) . " x " . $item['quantity'] . " = $" . number_format($item['price'] * $item['quantity'], 2) . "</li>";
    }
    echo "</ul>";

    echo "<p><strong>Total: $" . number_format(calculateTotal($cart), 2) . "</p>";
}

// Function to process the order (simulated - in a real system, this would involve payment processing, etc.)
function processOrder() {
    global $cart;
    $order_id++;
    echo "<h2>Order Confirmation</h2>";
    echo "<p>Order ID: " . $order_id . "</p>";
    echo "<p>Thank you for your order! Your items have been placed.</p>";

    // Clear the cart after order placement (optional)
    $cart = [];
}

// Handle form submission (if you have a form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $productId = (int)$_POST['product_id']; // Convert to integer
        $quantity = (int)$_POST['quantity'];

        addToCart($productId, $quantity);
        displayCart(); // Update the cart display
    } elseif (isset($_POST['checkout'])) {
        processOrder();
    }
}

// Display the initial cart
displayCart();

?>
