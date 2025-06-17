

<?php
session_start(); // Start the session

// --- Example Cart Logic ---

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// --- Functions to Manage the Cart ---

// Add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product not in cart, add it
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Remove an item from the cart by product ID
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Update the quantity of a cart item
function updateCartQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Get the contents of the cart
function getCartContents() {
  return $_SESSION['cart'];
}

// Calculate the total cart value
function calculateCartTotal() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
  }
  return $total;
}

// --- Example Usage / Cart Manipulation (Simulating User Actions) ---

// 1. Add an item to the cart
addToCart('product1', 'Awesome T-Shirt', 20, 2); // Add 2 T-shirts
addToCart('product2', 'Cool Mug', 10, 3);     // Add 3 Mugs

// 2. Remove an item from the cart
//removeCartItem('product1');

// 3. Update quantity
//updateCartQuantity('product2', 5);

// 4. Get the cart contents
$cart_items = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $id => $item) {
      echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";
}

// 5. Calculate and display the total
$total = calculateCartTotal();
echo "<p><strong>Total: $" . $total . "</strong></p>";


// --- Important Notes ---

// 1. Session Start:  `session_start();` *must* be called before any other code that uses session variables.

// 2. Product IDs:  In a real application, you would likely use a unique ID for each product.  This example uses 'product1', 'product2' as placeholders.

// 3. Persistence:  Session data is stored on the server.  It persists across multiple requests *from the same user*.  The user's browser doesn't store the session data itself (it stores a session ID).

// 4. Security:
//    - **Session ID Security:** The session ID is crucial.  Make sure it is generated using a strong, random method and that it is securely transmitted.  PHP's built-in session management is generally secure if you follow best practices.
//    - **Session Hijacking:** Be aware of session hijacking attacks.

// 5. Real-World Implementation:
//    - In a real e-commerce application, this would be integrated into a form handling system to collect user input and update the cart.
//    - You'd likely use a database to store product information.
//    - You'd have a system to handle user authentication and session management.
?>
