

<?php
session_start();

// --- Helper Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart(string $product_id, int $quantity = 1) {
    if (isset($_SESSION['cart'])) {
        // Item already in the cart, update the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Item not in the cart, add it
        $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
    }
}


/**
 * Removes an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart(string $product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}


/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity of the product.
 * @return void
 */
function updateQuantity(string $product_id, int $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}


/**
 *  Gets the items in the cart
 *
 * @return array|null Returns the cart items as an array, or null if the cart is empty.
 */
function getCart() {
    return $_SESSION['cart'] ?? null;
}


/**
 * Clears the entire cart
 *
 * @return void
 */
function clearCart() {
    unset($_SESSION['cart']);
}

// --- Example Usage (Simulating a Product Page) ---

// 1.  Add an item to the cart (e.g., when a user clicks an "Add to Cart" button)
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1; // Default to 1 if quantity not provided
    addToCart($product_id, $quantity);
    echo "<p>Item added to cart.</p>";
}


// 2. Remove an item from the cart (e.g., when a user clicks a "Remove from Cart" button)
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    removeFromCart($product_id);
    echo "<p>Item removed from cart.</p>";
}

// 3. Update the quantity of an item
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = intval($_POST['quantity']);
    updateQuantity($product_id, $new_quantity);
    echo "<p>Quantity updated in cart.</p>";
}



// 4. Display the cart contents (This is just an example)
$cart_items = getCart();

if ($cart_items) {
    echo "<h2>Your Cart</h2>";
    echo "<ul>";
    foreach ($cart_items as $product_id => $item) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Your cart is empty.</p>";
}

// 5. Clear the cart (e.g., when a user clicks a "Clear Cart" button)
if (isset($_POST['clear_cart'])) {
    clearCart();
    echo "<p>Cart cleared.</p>";
}



?>
