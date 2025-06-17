

<?php
session_start();

// Session variables to store cart items
$cart = [];

// --- Functions to handle cart operations ---

/**
 * Add an item to the cart.
 *
 * @param int $productId The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function add_to_cart(int $productId, int $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart array if it doesn't exist
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$productId])) {
    // If the product exists, increase the quantity
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    // If the product doesn't exist, add it to the cart with quantity 1
    $_SESSION['cart'][$productId] = ['quantity' => $quantity];
  }
}


/**
 * Update the quantity of an item in the cart.
 *
 * @param int $productId The ID of the product to update.
 * @param int $newQuantity The new quantity of the product.
 * @return void
 */
function update_cart_quantity(int $productId, int $newQuantity) {
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
function remove_from_cart(int $productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}


/**
 * Get the contents of the cart.
 *
 * @return array The cart array.
 */
function get_cart() {
  return $_SESSION['cart'];
}



// --- Example Usage (Demonstration) ---

// 1. Add an item to the cart
add_to_cart(123, 2); // Add product ID 123 with quantity 2

// 2. Add another item to the cart
add_to_cart(456, 1);

// 3. Update the quantity of product 123 to 5
update_cart_quantity(123, 5);


// 4. Remove product 456 from the cart
remove_from_cart(456);



// 5. Display the cart contents
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
$cart_items = get_cart();

if (empty($cart_items)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart_items as $productId => $item) {
        echo "<li>Product ID: " . $productId . ", Quantity: " . $item['quantity'] . "</li>";
    }
}
echo "</ul>";


// ---  Important Notes: ---

// 1.  Session Start: `session_start()` must be called at the beginning of every PHP script that uses sessions.  It initializes the session.

// 2. Persistence:  Session data (like the cart) is stored on the server.  When a user closes their browser, the session data is typically lost unless you use a persistent storage mechanism like a database or a cookie.

// 3.  Security:  Sessions should be used carefully for sensitive data.  Protect your session IDs using HTTPS and consider using encryption for data stored in sessions.

// 4. Data Validation:  Always validate user input (product IDs, quantities, etc.) to prevent errors and security vulnerabilities.

// 5.  Cart Structure: This example uses an associative array to represent the cart. The keys of the array are the product IDs, and the values are associative arrays containing the 'quantity' of each product.  You can adapt the cart structure to fit your specific needs.

// 6. Error Handling: In a real application, you would add more robust error handling and logging.

// 7.  Integration with your website: This is just a basic example.  You'll need to integrate this code into your website's logic, likely with form submissions to add items to the cart.
?>
