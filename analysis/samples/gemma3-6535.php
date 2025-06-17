

<?php
session_start();

// --- Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $productId The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($productId, $quantity = 1)
{
    if (isset($_SESSION['cart'])) {
        // Item already in cart, update quantity
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        // Item not in cart, add a new entry
        $_SESSION['cart'][$productId] = [
            'quantity' => $quantity,
            'name' => getProductName($productId) // Optionally, get the name for display
        ];
    }
}


/**
 * Gets the name of a product based on its ID.
 *  (This is a placeholder - replace with your actual database query)
 * @param int $productId The ID of the product.
 * @return string The product name, or an empty string if not found.
 */
function getProductName($productId)
{
    // Replace this with your actual database query.
    // This example just returns a hardcoded name for demonstration.
    if ($productId == 1) {
        return "Awesome T-Shirt";
    } elseif ($productId == 2) {
        return "Cool Mug";
    }
    return "";
}


/**
 * Retrieves the items in the cart.
 *
 * @return array The array of items in the cart.
 */
function getCartItems()
{
    return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
}


/**
 * Removes an item from the cart.
 *
 * @param int $productId The ID of the product to remove.
 * @return void
 */
function removeFromCart($productId)
{
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}


// --- Example Usage ---

// 1. Add an item to the cart
addToCart(1); // Adds one Awesome T-Shirt to the cart
addToCart(2, 2); // Adds two Cool Mugs to the cart

// 2. Get the cart items
$cart = getCartItems();
echo "<h2>Cart Items:</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $id => $item) {
        echo "<li>Product ID: " . $id . ", Quantity: " . $item['quantity'] . ", Name: " . $item['name'] . "</li>";
    }
    echo "</ul>";
}

// 3. Remove an item
removeFromCart(1); // Remove one Awesome T-Shirt

// 4. Get the cart items after removal
$cart = getCartItems();
echo "<br><br><h2>Cart Items After Removal:</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $id => $item) {
        echo "<li>Product ID: " . $id . ", Quantity: " . $item['quantity'] . ", Name: " . $item['name'] . "</li>";
    }
    echo "</ul>";
}


?>
