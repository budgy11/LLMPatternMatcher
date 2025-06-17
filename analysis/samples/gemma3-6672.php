

<?php

session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Helper Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product being added.
 * @param string $product_name The name of the product.
 * @param int    $price       The price of the product.
 * @param int    $quantity   The quantity of the product to add (default: 1).
 */
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = array(
      'name'     => $product_name,
      'price'    => $price,
      'quantity' => $quantity
    );
  } else {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  }
}

/**
 * Removes an item from the cart by product ID.
 *
 * @param string $product_id The ID of the product to remove.
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int    $new_quantity The new quantity of the product.
 */
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

/**
 * Gets the cart contents.
 *
 * @return array The cart array.
 */
function getCart() {
  return $_SESSION['cart'];
}


// --- Example Usage (Simulating User Interaction) ---

// Add a product to the cart
addToCart('product1', 'Awesome T-Shirt', 20, 2);

// Add another product to the cart
addToCart('product2', 'Cool Mug', 10, 1);

// Update the quantity of product1
updateQuantity('product1', 5);

// Get the cart contents
$cart = getCart();

// Display the cart contents
echo "<h2>Your Cart:</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_data) {
    echo "<li>" . $product_data['name'] . " - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
  }
  echo "</ul>";
}


// ---  Demonstration of Removing an Item ---
// removeFromCart('product2');

// Get the cart contents again to see the change
// $cart = getCart();
// echo "<h2>Cart after removal:</h2>";
// if (empty($cart)) {
//   echo "<p>Your cart is empty.</p>";
// } else {
//   echo "<ul>";
//   foreach ($cart as $product_id => $product_data) {
//     echo "<li>" . $product_data['name'] . " - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
//   }
//   echo "</ul>";
// }


?>
