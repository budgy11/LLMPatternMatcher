

<?php
session_start(); // Start the session

// Check if the cart is empty or not
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart as an empty array
}

// --- Example Functions for Cart Operations ---

/**
 * Adds an item to the cart.
 *
 * @param string $item_id   The ID of the item to add.
 * @param string $item_name The name of the item.
 * @param int    $quantity  The quantity of the item to add (default: 1).
 * @param float  $price     The price per unit of the item.
 */
function addToCart(string $item_id, string $item_name, int $quantity = 1, float $price) {
    if (!isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id] = [
            'name' => $item_name,
            'quantity' => $quantity,
            'price' => $price
        ];
    } else {
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
    }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $item_id The ID of the item to update.
 * @param int    $newQuantity The new quantity for the item.
 */
function updateCartItem(string $item_id, int $newQuantity) {
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] = $newQuantity;
    }
}

/**
 * Removes an item from the cart by its ID.
 *
 * @param string $item_id The ID of the item to remove.
 */
function removeCartItem(string $item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }
}

/**
 * Calculates the total cart value.
 */
function calculateCartTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['quantity'] * $item['price'];
        }
    }
    return $total;
}

// --- Example Usage (Demonstration) ---

// Add some items to the cart
addToCart('item1', 'Red T-Shirt', 2, 15.00);
addToCart('item2', 'Blue Jeans', 1, 45.00);

// Update the quantity of item1
updateCartItem('item1', 5);

// Calculate and display the total cart value
$total = calculateCartTotal();
echo "Total Cart Value: $" . number_format($total, 2) . "<br>";

// Display the contents of the cart
echo "<h2>Cart Contents:</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item_id => $item) {
        echo "<li>" . $item['name'] . " (Quantity: " . $item['quantity'] . ", Price: $" . number_format($item['price'], 2) . ")</li>";
    }
    echo "</ul>";
}

// Remove an item from the cart
//removeCartItem('item1');  // Uncomment to remove the item

// Display the cart again after removal (if uncommented)
//if (empty($_SESSION['cart'])) {
//    echo "<p>Your cart is empty.</p>";
//} else {
//   // ... (same as before to display cart)
//}
?>
