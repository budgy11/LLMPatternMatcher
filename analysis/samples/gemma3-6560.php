

<?php

// Start a session if it doesn't exist
session_start();

// Initialize the cart as an empty array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ---  Example Functions for Cart Operations ---

// Add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Increment the quantity if the product exists
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Add the product to the cart with initial quantity
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

// Remove an item from the cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Get the contents of the cart
function getCartContents() {
    return $_SESSION['cart'];
}

// Calculate the total cart value
function calculateTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total_item = $item['price'] * $item['quantity'];
            $total += $total_item;
        }
    }
    return $total;
}


// --- Example Usage (Simulating a form submission) ---

// Simulate a product being added to the cart
// In a real application, this would come from a form submission
// or AJAX request.
// Let's say a user clicked an "Add to Cart" button for product ID 1.
addToCart(1, "Awesome T-Shirt", 20.00, 2);

// Let's say a user clicked an "Add to Cart" button for product ID 2.
addToCart(2, "Cool Mug", 10.00, 1);

// Get the cart contents
$cart = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $product_details) {
        echo "<li>" . $product_details['name'] . " - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
    }
    echo "</ul>";
}

// Calculate and display the total
$total = calculateTotal();
echo "<p><strong>Total Cart Value: $" . $total . "</strong></p>";


// ---  Important Notes  ---

// 1. Session Start: The `session_start()` function *must* be called before any other code that uses the session.  It initializes the session and makes the session variables available.

// 2. Session Variables: Session variables are stored on the server and are associated with the user's browser session. This allows you to maintain cart data across multiple pages of a website.

// 3. Security:  Sessions are inherently insecure if not handled properly.  The `session_start()` function itself doesn't provide security. For more secure session handling, you should:
//   - Use HTTPS to encrypt communication between the browser and the server.
//   -  Implement proper session ID management (e.g., using secure random values).
//   -  Consider using session locking to prevent multiple users from accessing the same session.

// 4.  Data Storage:  This example stores cart data in the session.  For more complex applications, you might want to store cart data in a database or a persistent storage mechanism.

// 5.  Error Handling:  In a production environment, you would add error handling to handle cases where session variables are not set or contain invalid data.

// 6.  Scalability: For large-scale applications, consider using a more robust session management system or a dedicated shopping cart solution.
?>
