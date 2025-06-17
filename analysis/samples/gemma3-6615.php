

<?php
session_start(); // Start the session

// ----------------------------------------------------------------
// Function to add an item to the cart
// ----------------------------------------------------------------
function addToCart($product_id, $quantity) {
  // Check if the cart already exists, create it if not
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
  }
}

// ----------------------------------------------------------------
// Example Usage -  Simulate a product list and a user action
// ----------------------------------------------------------------

// Assume this is your product list (you'd typically fetch this from a database)
$products = array(
  1 => array('name' => 'Laptop', 'price' => 1200),
  2 => array('name' => 'Mouse', 'price' => 25),
  3 => array('name' => 'Keyboard', 'price' => 75)
);

// Simulate a user adding an item to the cart
// User wants to add 2 laptops and 3 mice
addToCart(1, 2);  // Product ID 1 (Laptop) - Quantity 2
addToCart(2, 3);  // Product ID 2 (Mouse) - Quantity 3


// ----------------------------------------------------------------
// Displaying the Cart Contents -  For Demonstration
// ----------------------------------------------------------------

echo "<h2>Your Shopping Cart</h2>";

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    $product_name = '';
    foreach ($products as $id => $product) {
      if ($id == $product_id) {
        $product_name = $product['name'];
        break;
      }
    }

    echo "<li>" . $product_name . " - Quantity: " . $item['quantity'] . " - Price: $" . $products[$product_id]['price'] . "</li>";
  }
  echo "</ul>";
} else {
  echo "<p>Your cart is empty.</p>";
}


// ----------------------------------------------------------------
// Notes and Considerations:
// ----------------------------------------------------------------

// 1. Session Start:  Always start the session using `session_start()` at the beginning of your script.

// 2. Data Storage:  The cart data is stored in the `$_SESSION` array.  This is a global, session-specific storage.

// 3. Cart Structure:
//    - `$_SESSION['cart']`: This array holds the items in the cart.
//    - Each element in the array is a product ID (e.g., 1, 2, 3).
//    - For each product ID, there's an associative array with:
//      - `quantity`: The number of that product in the cart.

// 4. Incrementing Quantity:
//   - If a product already exists in the cart, the code increments its quantity.
//   - If it doesn't exist, it adds it to the cart with a quantity of 1.

// 5.  Database Integration: In a real application, you'd replace the `$products` array with a database query to retrieve product information. You'd likely use a database query to add items to the cart.

// 6. Error Handling:  Add error handling (e.g., checking if quantity is valid).

// 7. Security:  In a production environment, consider security best practices (e.g., input validation, preventing XSS).

// 8.  Session Management: Implement proper session management to prevent session hijacking.

?>
