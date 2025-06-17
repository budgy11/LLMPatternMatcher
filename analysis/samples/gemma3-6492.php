

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --- Functions to manipulate the cart ---

// Add an item to the cart
function addToCart($product_id, $quantity = 1) {
    $product_id = (int)$product_id; // Ensure it's an integer

    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        $_SESSION['cart'][$product_id] += $quantity;
    }
}

// Remove an item from the cart
function removeItemFromCart($product_id) {
    $product_id = (int)$product_id; // Ensure it's an integer

    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    $product_id = (int)$product_id;

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Get the cart contents
function getCartContents() {
    return $_SESSION['cart'];
}

// Calculate the total price of the cart
function calculateTotal($cart) {
    $total = 0;
    foreach ($cart as $product_id => $quantity) {
        //  You'd need to replace this with your actual product price retrieval
        $product_price = getProductPrice($product_id);
        $total_for_item = $product_price * $quantity;
        $total = $total + $total_for_item;
    }
    return $total;
}


// --- Example Usage ---

// Add an item to the cart
addToCart(123, 2); // Add 2 items of product ID 123
addToCart(456, 1);  // Add 1 item of product ID 456

// Display the cart contents
echo "<h2>Your Cart:</h2>";
echo "<ul>";
$cartContents = getCartContents();

foreach ($cartContents as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
}
echo "</ul>";

// Calculate and display the total
$total = calculateTotal($cartContents);
echo "<p>Total: $" . $total . "</p>";


// Remove an item
removeItemFromCart(123);

// Display updated cart
echo "<p>Your Cart (after removing item):</p>";
echo "<ul>";
$cartContents = getCartContents();
foreach ($cartContents as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
}
echo "</ul>";
?>
