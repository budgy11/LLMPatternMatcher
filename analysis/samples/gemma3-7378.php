

<?php

// Sample Product Data (replace with database integration in a real application)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20.00],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50.00],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15.00],
];

// Function to handle the purchase process
function handlePurchase($cart, $total) {
    // In a real application, you'd process payment here (e.g., PayPal, Stripe)
    // For this example, we'll just print a confirmation message.

    echo "<p><strong>Order Confirmation:</strong></p>";
    echo "<p>Items Purchased: </p>";
    echo "<ul>";
    foreach ($cart as $itemId => $quantity) {
        $product = $products[$itemId];
        echo "<li>$product['name'] x $quantity - Total: $" . number_format($product['price'] * $quantity, 2) . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total Amount:</strong> $" . number_format($total, 2) . "</p>";
    echo "<p>Thank you for your purchase!</p>";
}

// --------------------  Example Usage  --------------------

// Initialize the cart (empty initially)
$cart = [];

// Check if there's a request to add an item to the cart
if (isset($_GET['add_to_cart'])) {
    $itemId = (int)$_GET['add_to_cart']; // Convert to integer for safety

    if (isset($products[$itemId])) {
        $product = $products[$itemId];
        $quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1; // Default to 1 if quantity isn't provided

        if ($quantity > 0) {
            // Add to cart
            if (isset($cart[$itemId])) {
                $cart[$itemId] += $quantity;
            } else {
                $cart[$itemId] = $quantity;
            }

            // Update the URL to show the cart contents
            $_SESSION['cart'] = $cart;  // Store cart in session
            echo "<p>Item added to cart. <a href='cart.php'>View Cart</a></p>"; // Link to cart page
        } else {
            echo "<p>Please enter a valid quantity.</p>";
        }
    } else {
        echo "<p>Product not found.</p>";
    }
}

// Display the cart contents (if the cart is not empty)
if (!empty($cart)) {
    $total = 0;
    echo "<h2>Your Shopping Cart:</h2>";
    echo "<ul>";
    foreach ($cart as $itemId => $quantity) {
        $product = $products[$itemId];
        $itemTotal = $product['price'] * $quantity;
        $total += $itemTotal;
        echo "<li>$product['name'] x $quantity - Total: $" . number_format($itemTotal, 2) . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total Amount:</strong> $" . number_format($total, 2) . "</p>";
    handlePurchase($cart, $total);  // Call the purchase confirmation function
} else {
    echo "<p>Your shopping cart is empty.</p>";
}

?>
