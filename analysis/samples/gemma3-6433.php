

<?php

// Start session if it's not already started
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Functions for Cart Operations ---

/**
 * Adds a product to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param string $product_name The name of the product (for display).
 * @param float $price The price of the product.
 * @param int $quantity The quantity to add (default: 1).
 */
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    // Product not in cart, add it with initial quantity of 1
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  } else {
    // Product already in cart, increase quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  }
}


/**
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity.
 */
function update_cart_quantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}


/**
 * Removes a product from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 */
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}


/**
 * Gets all items in the cart.
 *
 * @return array  An array representing the cart contents.
 */
function get_cart_items() {
  return $_SESSION['cart'];
}


/**
 * Calculates the total cart value.
 *
 * @return float The total cart value.
 */
function calculate_cart_total() {
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// --- Example Usage (Simulated product information - in a real application, you'd get this from a database) ---

$products = array(
    1 => array('name' => 'Laptop', 'price' => 1200.00),
    2 => array('name' => 'Mouse', 'price' => 25.00),
    3 => array('name' => 'Keyboard', 'price' => 75.00)
);

// --- Handling User Interactions (Simulated - in a real application, you'd handle form submissions, AJAX, etc.) ---

// Example: Add a product to the cart
if (isset($_POST['add_to_cart'])) {
  $product_id = (int)$_POST['product_id'];
  $product_name = $products[$product_id]['name'];
  $price = $products[$product_id]['price'];
  $quantity = (int)$_POST['quantity'];

  add_to_cart($product_id, $product_name, $price, $quantity);
  echo "Product '$product_name' added to cart.  Cart total: " . calculate_cart_total() . "<br>";
}

// Example: Update quantity of a product
if (isset($_POST['update_quantity'])) {
  $product_id = (int)$_POST['product_id'];
  $new_quantity = (int)$_POST['quantity'];
  update_cart_quantity($product_id, $new_quantity);
  echo "Quantity of product '$product_name' updated to $new_quantity.  Cart total: " . calculate_cart_total() . "<br>";
}


// Example: Remove a product from the cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = (int)$_POST['product_id'];
    remove_from_cart($product_id);
    echo "Product '$product_name' removed from cart. Cart total: " . calculate_cart_total() . "<br>";
}



// --- Displaying the Cart (Simulated) ---
echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    $product_name = $item['name'];
    $price = $item['price'];
    $quantity = $item['quantity'];
    echo "<li>Product: " . $product_name . ", Price: $" . $price . ", Quantity: " . $quantity . " - Total: $" . $price * $quantity . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total Cart Value:</strong> $" . calculate_cart_total() . "</p>";
}


?>
