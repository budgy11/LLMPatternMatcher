

<?php
session_start();

// --- Add to Cart Functions ---

/**
 * Adds an item to the shopping cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.  Defaults to 1.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  if (isset($_SESSION['cart'])) {
    // If cart already exists, check if the product is already in it
    if (isset($_SESSION['cart'][$product_id])) {
      // Product is already in cart, increment quantity
      $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
      // Product not in cart, add it with quantity 1
      $_SESSION['cart'][$product_id] = array(
        'quantity' => $quantity,
        'name' => "Product Name (Placeholder)" // Replace with actual product name
      );
    }
  } else {
    // Cart doesn't exist, initialize it with the new product
    $_SESSION['cart'] = array(
      $product_id => array(
        'quantity' => $quantity,
        'name' => "Product Name (Placeholder)" // Replace with actual product name
      )
    );
  }
}


// --- Example Usage ---

// Add a product to the cart
addToCart(123); // Add product ID 123 in quantity 1

// Add product ID 123 in quantity of 2
addToCart(123, 2);

// Add a different product
addToCart(456, 1);

// Display the cart contents (for demonstration)
echo "<h2>Shopping Cart</h2>";
if (isset($_SESSION['cart'])) {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $product_details['quantity'] . ", Name: " . $product_details['name'] . "</li>";
  }
  echo "</ul>";
} else {
  echo "<p>Your cart is empty.</p>";
}


// ---  Demonstration of Removing items from the cart (Optional) ---

/**
 * Removes a product from the shopping cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Example: Remove product ID 123
// removeFromCart(123);

//  (To actually run the removal, uncomment the call above)
//  echo "<h2>Shopping Cart (After Removal)</h2>";
//  if (isset($_SESSION['cart'])) {
//    echo "<ul>";
//    foreach ($_SESSION['cart'] as $product_id => $product_details) {
//      echo "<li>Product ID: " . $product_id . ", Quantity: " . $product_details['quantity'] . ", Name: " . $product_details['name'] . "</li>";
//    }
//    echo "</ul>";
//  } else {
//    echo "<p>Your cart is empty.</p>";
//  }
?>
