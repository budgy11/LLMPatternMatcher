

<?php
session_start();

// Define the cart as an array.  This could be a database table, a file, etc.
// For simplicity, we'll use an array here.
$cart = [];

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addItemToCart(int $product_id, int $quantity) {
    if (!isset($cart[$product_id])) {
        $cart[$product_id] = 0; // Initialize quantity if not already present
    }
    $cart[$product_id] += $quantity;
}

/**
 * Retrieves the cart contents.
 *
 * @return array The cart as an array of product IDs and quantities.
 */
function getCart() {
    return $cart;
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity of the product.
 * @return void
 */
function updateCartItem(int $product_id, int $new_quantity) {
    if (isset($cart[$product_id])) {
        $cart[$product_id] = $new_quantity;
    }
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeItemFromCart(int $product_id) {
    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
    }
}

/**
 * Clears the entire cart.
 *
 * @return void
 */
function clearCart() {
    $cart = [];
}

// Example Usage:  This is just for demonstration.  You'd likely get this data
// from a form submission or API call.

// 1. Add an item to the cart
addItemToCart(1, 2); // Add 2 of product ID 1
addItemToCart(2, 1); // Add 1 of product ID 2


// 2. Get the current cart contents
$current_cart = getCart();
echo "Current Cart Contents: <br>";
print_r($current_cart);

// 3. Update an item's quantity
updateCartItem(1, 5); // Change quantity of product 1 to 5
echo "<br>Cart after updating quantity of product 1: <br>";
print_r($current_cart);

// 4. Remove an item
removeItemFromCart(2);
echo "<br>Cart after removing product 2: <br>";
print_r($current_cart);


// 5. Clear the cart
clearCart();
echo "<br>Cart after clearing: <br>";
print_r($current_cart);

//  Demonstrating session usage (saving and retrieving the cart)

//  This example shows how you'd store the cart in a session.
if (isset($_POST['add_to_cart'])) {
    // Simulate a form submission
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    addItemToCart($product_id, $quantity);
    //  You would typically handle a redirect here after adding to the cart.
    //  For example:  header("Location: cart.php");
}

if (isset($_GET['clear_cart'])) {
    clearCart();
    // Redirect to the cart page or other appropriate action
    header("Location: cart.php");
}

?>
