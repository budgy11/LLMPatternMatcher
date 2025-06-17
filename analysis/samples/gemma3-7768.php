
</body>
</html>


<?php

// This script demonstrates a basic purchase functionality.
// In a real application, you'd likely use a database for storing products,
// user information, and orders.  This is a simplified example.

// --- Product Data (Simulated Database) ---
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20.00],
    2 => ['id' => 2, 'name' => 'Mug', 'price' => 10.00],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15.00],
];

// --- Session Management (For cart persistence) ---
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --- Functions ---

/**
 * Add a product to the cart.
 *
 * @param int $productId The ID of the product to add.
 * @param int $quantity The quantity to add (default: 1).
 */
function addToCart(int $productId, int $quantity = 1) {
    if (!isset($products[$productId])) {
        echo "<p>Product with ID $productId not found.</p>";
        return;
    }

    if (!isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = 0; //Initialize if not present
    }

    $_SESSION['cart'][$productId] += $quantity;
}


/**
 * Calculate the total cost of the cart.
 *
 * @return float The total cost.
 */
function calculateTotal() {
    $total = 0.00;
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        if (isset($products[$productId])) {
            $totalPrice = $products[$productId]['price'] * $quantity;
            $totalPrice = $total + $totalPrice;
            $total = $totalPrice; //Update total
        }
    }
    return $total;
}

/**
 * Display the cart contents.
 */
function displayCart() {
    echo "<h2>Your Shopping Cart</h2>";
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        if (isset($products[$productId])) {
            $productName = $products[$productId]['name'];
            $productPrice = $products[$productId]['price'];
            echo "<li>$productName - Quantity: $quantity - Price: $productPrice</li>";
        }
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . number_format(calculateTotal(), 2) . "</strong></p>";
}



// --- Handle Purchase (Simplified - No actual payment processing) ---

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit_order'])) {
        // In a real application, you'd integrate with a payment gateway here.
        // This is just a placeholder for order processing logic.

        // Clear the cart after successful order placement (or redirect)
        session_destroy();
        session_start();  //Restart session to reset
        $_SESSION['cart'] = [];

        echo "<p>Order placed successfully!  Your cart has been cleared.</p>";
    }
}
?>
