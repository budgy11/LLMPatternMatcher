

<?php
session_start();

// Check if the session is empty, if so, initialize the cart
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Example Functions for Cart Operations ---

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment quantity if it exists
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  } else {
    // Handle case where the item is not in the cart
    // You could either:
    // 1.  Ignore the request (do nothing)
    // 2.  Throw an error (for debugging)
    // 3.  Add the item with the specified quantity (if it doesn't exist)
    // This example will ignore the request.
    // echo "Product ID $product_id not found in cart. Request ignored.
";
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}


// --- Example Usage (Simulating User Interaction) ---

// 1. Adding an item
add_to_cart(1, "Laptop", 1200, 1); // Product ID 1, Laptop, $1200, Quantity 1
add_to_cart(2, "Mouse", 25, 2);   // Product ID 2, Mouse, $25, Quantity 2

// 2. Updating quantity
update_cart_quantity(1, 3); // Increase Laptop quantity to 3

// 3. Removing an item
remove_from_cart(2); // Remove Mouse from cart

// --- Displaying the Cart Contents ---
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_data) {
    echo "<li>";
    echo "<strong>" . $product_data['name'] . "</strong> - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
  }
  echo "</ul>";
}

// --- Important Notes ---

// 1. Session Start:  `session_start()` is crucial. It initializes the PHP session, 
//    allowing you to store and retrieve data across multiple pages.  It *must* be called 
//    before any output is sent to the browser (e.g., before `echo`, `print`, etc.).

// 2. Session Data:  The `$_SESSION` array is the PHP mechanism for storing session data.

// 3. Cart Structure: The example uses an associative array (`$_SESSION['cart']`) where the 
//    keys are product IDs and the values are associative arrays containing product details 
//    (name, price, quantity).  You can customize this structure to fit your specific needs.

// 4. Security:
//    - Always sanitize and validate user input to prevent security vulnerabilities (e.g., SQL injection, XSS).
//    - Implement proper authentication and authorization to protect your session data.

// 5. Persistence: Session data is stored on the server and persists until the session expires or is explicitly destroyed.  You can configure session lifetime (e.g., by setting the `session.gc_maxlifetime` in your php.ini file).  Consider using a database to store session data for more robust persistence.

?>
