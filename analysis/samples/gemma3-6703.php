

<?php
session_start();

// --- Session Management Functions ---

/**
 * Add an item to the cart.
 *
 * @param int $productId The ID of the product to add.
 * @param int $quantity The quantity to add.
 * @return void
 */
function addToCart(int $productId, int $quantity = 1)
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = []; // Initialize the cart if it doesn't exist
    }

    if (isset($_SESSION['cart'][$productId])) {
        // Item already in cart, increase quantity
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        // Item not in cart, add it with quantity 1
        $_SESSION['cart'][$productId] = ['quantity' => $quantity];
    }
}

/**
 * Update the quantity of an item in the cart.
 *
 * @param int $productId The ID of the product to update.
 * @param int $newQuantity The new quantity for the product.
 * @return void
 */
function updateCartItem(int $productId, int $newQuantity)
{
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
    }
}

/**
 * Remove an item from the cart.
 *
 * @param int $productId The ID of the product to remove.
 * @return void
 */
function removeCartItem(int $productId)
{
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

/**
 * Get the items in the cart.
 *
 * @return array An array representing the cart contents.
 */
function getCart()
{
    return $_SESSION['cart'] ?? []; // Return an empty array if cart isn't initialized
}

/**
 * Clear the entire cart.
 *
 * @return void
 */
function clearCart()
{
    unset($_SESSION['cart']);
}

// --- Example Usage (Illustrative - Replace with your actual logic) ---

// 1. Add an item to the cart:
addToCart(123); // Add product ID 123 to the cart with quantity 1
addToCart(456, 2); // Add product ID 456 to the cart with quantity 2

// 2. Update an item's quantity:
updateCartItem(123, 5); // Increase quantity of product 123 to 5

// 3. Get the cart contents:
$cart = getCart();
print_r($cart); // This will show you the contents of the cart (e.g., [123 => ['quantity' => 5], 456 => ['quantity' => 2]])

// 4. Remove an item:
removeCartItem(456);

// 5. Get the cart contents after removal:
$cart = getCart();
print_r($cart); // Now it should only contain [123 => ['quantity' => 5]]

// 6. Clear the cart:
clearCart();
print_r($cart); // It will be an empty array [].

?>
