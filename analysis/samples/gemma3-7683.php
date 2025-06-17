
</body>
</html>


<?php

// Configuration (Replace with your actual values)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00, 'quantity' => 10],
    2 => ['name' => 'Jeans', 'price' => 50.00, 'quantity' => 5],
    3 => ['name' => 'Hat', 'price' => 15.00, 'quantity' => 20],
];

$cart = []; // Initialize an empty shopping cart

// Function to add an item to the cart
function addToCart($productId, $quantity = 1, $cart) {
    if (isset($products[$productId])) {
        if ($quantity > 0) {
            $product = $products[$productId];
            $cart[] = ['product_id' => $productId, 'product' => $product, 'quantity' => $quantity];
            echo "Added " . $quantity . " " . $product['name'] . " to your cart. ";
        } else {
            echo "Invalid quantity. Please enter a positive number.";
        }
    } else {
        echo "Product ID " . $productId . " not found.";
    }
}

// Function to display the cart
function displayCart($cart) {
    echo "<h2>Your Shopping Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($cart as $item) {
        $product = $products[$item['product_id']];
        $total_price = $product['price'] * $item['quantity'];
        echo "<li>" . $product['name'] . " - $" . number_format($product['price'], 2) . " x " . $item['quantity'] . " = $" . number_format($total_price, 2) . "</li>";
    }
    echo "</ul>";

    // Calculate total cart value
    $totalCartValue = 0;
    foreach ($cart as $item) {
        $product = $products[$item['product_id']];
        $total_price = $product['price'] * $item['quantity'];
        $totalCartValue += $total_price;
    }

    echo "<p><b>Total Cart Value: $" . number_format($totalCartValue, 2) . "</p>";

    // Display checkout link
    echo "<a href='checkout.php'>Proceed to Checkout</a>"; // Replace with your checkout page.
}

// Function to handle checkout (placeholder)
function checkout() {
    echo "<h2>Checkout</h2>";
    echo "<p>Processing your order...</p>";
    // In a real application, you would:
    // 1. Collect payment information
    // 2. Verify payment
    // 3. Update inventory
    // 4. Send order confirmation email

    // For this example, we'll just display a confirmation.
    echo "<p>Order placed successfully!  Your order details will be emailed to you.</p>";
}



// --- Example Usage (Handling User Input) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Add to Cart functionality
    if (isset($_POST['add_to_cart'])) {
        $productId = (int)$_POST['product_id']; // Cast to integer for safety.  Important!
        $quantity = (int)$_POST['quantity']; // Cast to integer
        addToCart($productId, $quantity);
    }

    // Display the cart
    displayCart($cart);
}

?>
