

<?php
session_start();

// --- Helper Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product to add.
 * @param string $product_name The name of the product.
 * @param int $quantity The quantity of the product to add.
 * @param float $price The price of the product.
 *
 * @return void
 */
function addToCart(string $product_id, string $product_name, int $quantity, float $price): void
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the item already exists in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Increment the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Add the item to the cart
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'quantity' => $quantity,
            'price' => $price
        ];
    }
}


/**
 * Retrieves the contents of the cart.
 *
 * @return array  The cart contents as an array.
 */
function getCartContents(): array
{
    return $_SESSION['cart'] ?? []; // Return empty array if cart is not set.
}

/**
 * Calculates the total price of the cart.
 *
 * @return float  The total price.
 */
function calculateTotalPrice(): float
{
    $total = 0.0;
    $cart = getCartContents();

    foreach ($cart as $item) {
        $totalItemPrice = $item['quantity'] * $item['price'];
        $total += $totalItemPrice;
    }

    return $total;
}

/**
 * Removes an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 *
 * @return void
 */
function removeCartItem(string $product_id): void
{
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Updates the quantity of an item in the cart
 *
 * @param string $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function updateCartItem(string $product_id, int $quantity): void
{
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


// --- Example Usage / Demonstration ---

// 1. Add an item to the cart
addToCart('product1', 'T-Shirt', 2, 20.00); // Adds two T-shirts
addToCart('product2', 'Jeans', 1, 50.00);  // Adds one pair of jeans

// 2. Display the cart contents
$cart = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $item_id => $product_info) {
        echo "<li>" . $product_info['name'] . " - Quantity: " . $product_info['quantity'] . " - Price: $" . $product_info['price'] . "</li>";
    }
    echo "</ul>";
}


// 3. Calculate and display the total price
$totalPrice = calculateTotalPrice();
echo "<p><strong>Total Price: $" . $totalPrice . "</strong></p>";

// 4. Update the quantity of an item
updateCartItem('product1', 3); // Increase the quantity of T-Shirts to 3

// 5. Display the cart contents after update
$cart = getCartContents();
echo "<p>After updating the quantity, the cart contents are:</p>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $item_id => $product_info) {
        echo "<li>" . $product_info['name'] . " - Quantity: " . $product_info['quantity'] . " - Price: $" . $product_info['price'] . "</li>";
    }
    echo "</ul>";
}

// 6. Remove an item from the cart
removeCartItem('product2');

// 7. Display the cart contents after removing item
$cart = getCartContents();
echo "<p>After removing item, the cart contents are:</p>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $item_id => $product_info) {
        echo "<li>" . $product_info['name'] . " - Quantity: " . $product_info['quantity'] . " - Price: $" . $product_info['price'] . "</li>";
    }
    echo "</ul>";
}


?>
