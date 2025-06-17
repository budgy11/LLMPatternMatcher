

<?php

// Start a session if it doesn't exist
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// --- Product Information (For Demonstration) ---
// In a real application, you would fetch this from a database.
$products = array(
    array('id' => 1, 'name' => 'Laptop', 'price' => 1200),
    array('id' => 2, 'name' => 'Mouse', 'price' => 25),
    array('id' => 3, 'name' => 'Keyboard', 'price' => 75),
);

// --- Functions to Handle Cart Operations ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 */
function addToCart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        // If the product isn't in the cart yet, add it with the specified quantity
        $_SESSION['cart'][$product_id] = array('quantity' => $quantity, 'price' => $product_id);  // Use product ID as the key, assuming prices are associated with the ID
    } else {
        // If the product is already in the cart, increase the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 */
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Calculates the total cart value.
 *
 * @return float The total cart value.
 */
function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

/**
 * Gets the items in the cart.
 *
 * @return array An array of items in the cart.
 */
function getCartItems() {
    return $_SESSION['cart'];
}

// --- Example Usage / Cart Interaction ---

// 1. Add a product to the cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($product_id, $quantity);
    // Optionally, display a success message
    echo "<p>Product added to cart.</p>";
}

// 2. Remove an item from the cart (e.g., via a delete button)
if (isset($_GET['remove_from_cart'])) {
    $product_id = $_GET['remove_from_cart'];
    removeFromCart($product_id);
    // Optionally, display a success message
    echo "<p>Product removed from cart.</p>";
}

// 3. Display the cart contents
echo "<h2>Cart Items:</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item_id => $item) {
        $product_name = 'Product ' . $item_id; // Replace with actual product name lookup

        echo "<li>" . $product_name . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
}


// 4. Calculate and display the total
echo "<p><strong>Total: $" . calculateTotal() . "</strong></p>";

?>
