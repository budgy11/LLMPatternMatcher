

<?php
session_start();

// Function to add an item to the cart
function addToCart($item_id, $item_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  $_SESSION['cart'][$item_id] = array(
    'name' => $item_name,
    'price' => $price,
    'quantity' => $quantity
  );
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($item_id, $quantity) {
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] = $quantity;
    }
}


// Function to remove an item from the cart
function removeFromCart($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

//  ---  Example Usage  ---

// 1. Add an item to the cart
addToCart(1, 'T-Shirt', 20, 2);
addToCart(2, 'Jeans', 50, 1);

// 2. Update the quantity of an item
updateCartQuantity(1, 5); // Increase the quantity of T-Shirt to 5

// 3. Get the cart contents
$cart = getCartContents();
echo "<h1>Your Cart:</h1>";
echo "<pre>";
print_r($cart);
echo "</pre>";

// 4. Remove an item
removeFromCart(2);

// After removing the Jeans, re-display the cart
$cart = getCartContents();
echo "<h1>Your Cart (After Removal):</h1>";
echo "<pre>";
print_r($cart);
echo "</pre>";


//  ---  Considerations and Next Steps  ---

// 1.  Database Integration: This example uses a simple associative array in the session to store the cart data.  For a real application, you would typically store this data in a database (e.g., MySQL) for persistence.

// 2.  Session Management:
//     *  `session_start()` must be called at the beginning of every PHP script that uses sessions.
//     *  You need to set a session ID and transmit it to the browser.  This is usually done automatically when the user visits your website for the first time.
//     *  Consider using a session cookie to store the session ID.

// 3.  Error Handling: Add error handling (e.g., check if the item ID is valid before adding to the cart).

// 4.  Security:
//     *  Use HTTPS to protect session IDs from being intercepted.
//     *  Consider using secure session cookies.
//     *  Implement proper input validation to prevent cross-site scripting (XSS) attacks.

// 5.  User Interface: This example only provides the backend logic for the cart.  You'll need to create a user interface (e.g., HTML forms, JavaScript) to allow users to add, update, and remove items from the cart.

// 6.  Quantity Validation:  Ensure that the quantity added/updated is a valid number.
?>
