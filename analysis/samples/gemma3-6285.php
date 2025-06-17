

<?php
session_start(); // Start the session

// --- Cart Management Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
    if (isset($_SESSION['cart'])) {
        // Item already in cart - increment quantity
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        // Item not in cart - add it
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Gets the cart contents.
 *
 * @return array The cart contents as an associative array.
 */
function getCart() {
    return $_SESSION['cart'] ?? []; // Return cart or empty array if session doesn't exist
}

/**
 * Gets the total number of items in the cart
 *
 * @return int
 */
function getCartTotal() {
    $total = 0;
    if(isset($_SESSION['cart'])) {
        foreach($_SESSION['cart'] as $quantity) {
            $total += $quantity;
        }
    }
    return $total;
}


/**
 * Clears the entire cart.
 *
 * @return void
 */
function clearCart() {
    unset($_SESSION['cart']);
}


// --- Example Usage (Simulating a product list - replace with your actual product data) ---

$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75],
];

// ---  Add to Cart Examples ---
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity']; // Allow for different quantities
    addToCart($product_id, $quantity);
    echo "<p>Item added to cart.</p>";
}

// --- Display Cart Contents ---
$cart = getCart();

echo "<h2>Your Shopping Cart</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $quantity) {
        $product = $products[$product_id];
        echo "<li>" . $product['name'] . " - Quantity: " . $quantity . " - Price: $" . $product['price'] . "</li>";
    }
    echo "</ul>";
    echo "<p>Total Items: " . getCartTotal() . "</p>";
}

// --- Example Remove Item ---
if (isset($_POST['remove_item'])) {
    $product_id = (int)$_POST['product_id'];
    removeFromCart($product_id);
    echo "<p>Item removed from cart.</p>";
}

// --- Clear Cart (Example - usually a button on your page) ---
if (isset($_POST['clear_cart'])) {
    clearCart();
    echo "<p>Cart cleared.</p>";
}

?>
