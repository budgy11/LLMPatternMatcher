

<?php
session_start();

// --- Cart Session Variables ---

// Session to store the cart data (array of product IDs and quantities)
$cart = [];

// Session to store the total cart value
$_SESSION['cart_total'] = 0;

// Session to store the number of items in the cart
$_SESSION['cart_item_count'] = 0;



// --- Helper Functions ---

/**
 * Adds a product to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1)
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = []; // Initialize the cart if it doesn't exist
    }

    if (isset($_SESSION['cart'][$product_id])) {
        // Product already in cart, increase quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Product not in cart, add it
        $_SESSION['cart'][$product_id] = [
            'quantity' => $quantity,
            'price'    => 0 // You'll need to store the product price here (e.g., from a database)
        ];
    }

    // Update cart total
    $_SESSION['cart_total'] += $_SESSION['cart'][$product_id]['quantity'] * 0; // 0 price (for now, price comes from database)

    // Update item count
    $_SESSION['cart_item_count']++;
}


/**
 * Removes a product from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id)
{
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }

    // Update cart total
    $_SESSION['cart_total'] -= $_SESSION['cart'][$product_id]['quantity'] * 0;

    // Update item count
    $_SESSION['cart_item_count']--;
}


/**
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity of the product.
 * @return void
 */
function updateQuantity($product_id, $quantity)
{
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
    // Update cart total
    $_SESSION['cart_total'] -= $_SESSION['cart'][$product_id]['quantity'] * 0;
    $_SESSION['cart_total'] += $quantity * 0;  // Price is assumed to be 0 (for this example)
    // Update item count
    $_SESSION['cart_item_count'] = 0;
    foreach ($_SESSION['cart'] as $item) {
      $_SESSION['cart_item_count']++;
    }
}

/**
 * Clears the entire cart.
 *
 * @return void
 */
function clearCart()
{
    unset($_SESSION['cart']);
    $_SESSION['cart_total'] = 0;
    $_SESSION['cart_item_count'] = 0;
}


// --- Example Usage (Illustrative -  This would normally be handled by your website's front-end) ---

// Add a product to the cart
addToCart(1, 2); // Add 2 of product with ID 1

// Add another product to the cart
addToCart(3, 1);

// Display the cart contents (for demonstration)
echo "<h2>Cart Contents:</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
    }
    echo "</ul>";
    echo "<p>Cart Total: $" . number_format($_SESSION['cart_total'], 2) . "</p>";
    echo "<p>Total Items in Cart: " . $_SESSION['cart_item_count'] . "</p>";
}

// Remove a product from the cart
removeFromCart(1);

// Update the quantity of a product
updateQuantity(3, 3);

// Clear the cart
//clearCart();
?>
