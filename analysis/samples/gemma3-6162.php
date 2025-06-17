

<?php
session_start(); // Start the session

// --- Cart Functions ---

/**
 * Add an item to the cart.
 *
 * @param string $product_id The ID of the product to add.
 * @param string $product_name The name of the product.
 * @param int $quantity The quantity to add.
 * @param float $price The price of the single product.
 *
 * @return void
 */
function addToCart(string $product_id, string $product_name, int $quantity, float $price) {
    if (empty($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][] = [
        'id' => $product_id,
        'name' => $product_name,
        'quantity' => $quantity,
        'price' => $price
    ];
}


/**
 * Update the quantity of an item in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity.
 *
 * @return void
 */
function updateCartQuantity(string $product_id, int $new_quantity) {
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$item) { // Use &$item to modify the original array
            if ($item['id'] == $product_id) {
                $item['quantity'] = $new_quantity;
                break;
            }
        }
    }
}


/**
 * Remove an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 *
 * @return void
 */
function removeFromCart(string $product_id) {
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $product_id) {
                unset($_SESSION['cart'][$key]);
                //  Optional:  You could re-index the array if needed.
                //  unset($_SESSION['cart']);
                //  $_SESSION['cart'] = array_values($_SESSION['cart']);
                break;
            }
        }
    }
}



/**
 * Calculate the total cart value.
 *
 * @return float The total value.
 */
function calculateCartTotal() {
    $total = 0;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['quantity'] * $item['price'];
        }
    }
    return round($total, 2); // Round to 2 decimal places
}

/**
 * Get all items in the cart
 *
 * @return array An array of items in the cart
 */
function getCartItems() {
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        return $_SESSION['cart'];
    }
    return [];
}



// --- Example Usage (Simulating User Actions) ---

// 1. Add a product to the cart
addToCart('product1', 'T-Shirt', 2, 20.00);

// 2. Add another product
addToCart('product2', 'Jeans', 1, 50.00);

// 3. Update the quantity of product1 to 3
updateCartQuantity('product1', 3);

// 4. Remove product2 from the cart
removeFromCart('product2');

// 5. Calculate and display the total
$total = calculateCartTotal();
echo "Cart Total: $" . $total . "<br>";

// 6. Get items in cart
$cartItems = getCartItems();
echo "Cart Items: <br>";
echo "<pre>";
print_r($cartItems);
echo "</pre>";



// ---  Important Notes ---

// 1. Session Start:  `session_start();` MUST be called at the beginning of your script to initialize the session.
// 2. Persistence: Sessions store data on the server.  This data is associated with the user's browser.
// 3. Security:  Be mindful of security.  Avoid storing sensitive information directly in sessions.
// 4.  Error Handling: Add proper error handling (e.g., check if the product exists, handle invalid input).
// 5.  Database Integration:  In a real application, you would likely fetch product details (name, price, ID) from a database.
// 6.  Cart Data Structure: The array structure `$_SESSION['cart']` is just an example.  You can adapt it to your specific needs.  Consider using an object-oriented approach if your application becomes more complex.
?>
