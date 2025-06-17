

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// --- Functions to Manage the Cart ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        $_SESSION['cart'][$product_id] += $quantity;
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
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity of the product.
 * @return void
 */
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

/**
 * Gets the total items in the cart.
 *
 * @return int The total number of items in the cart.
 */
function getCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $quantity) {
        $total += $quantity;
    }
    return $total;
}


// --- Example Usage (Simulated Product Data) ---

$products = array(
    1 => array('name' => 'Laptop', 'price' => 1200),
    2 => array('name' => 'Mouse', 'price' => 25),
    3 => array('name' => 'Keyboard', 'price' => 75)
);


// --- Handle User Actions (Simulated) ---

// Example 1: Adding a product to the cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($product_id, $quantity);
    echo "<p>Added " . $quantity . " " . $products[$product_id]['name'] . " to your cart.</p>";
}

// Example 2: Removing an item from the cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    removeFromCart($product_id);
    echo "<p>Removed " . $products[$product_id]['name'] . " from your cart.</p>";
}

// Example 3: Updating quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    updateQuantity($product_id, $new_quantity);
    echo "<p>Updated quantity of " . $products[$product_id]['name'] . " to " . $new_quantity . "</p>";
}

// --- Display the Cart ---
echo "<br>";
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product = $products[$product_id];
        echo "<li>" . $product['name'] . " - Quantity: " . $quantity . " - Price: $" . $product['price'] . "</li>";
    }
    echo "</ul>";
    echo "<p>Total Items in Cart: " . getCartTotal() . "</p>";
}

?>
