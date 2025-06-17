

<?php

// Start a session (if not already started)
session_start();

// --- Cart Logic ---

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart session exists
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart as an empty array
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to get all items in the cart
function get_cart_items() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  } else {
    return [];
  }
}

// Function to calculate the cart total
function calculate_cart_total() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
  }
  return $total;
}

// --- Example Usage (Simulating User Interactions) ---

// Add some items to the cart
add_to_cart(1, 'Laptop', 1200, 1);
add_to_cart(2, 'Mouse', 25, 2);
add_to_cart(1, 'Laptop', 1200); // Add another laptop

// Display the cart contents
$cart_items = get_cart_items();
echo "<h2>Your Cart</h2>";
if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

// Calculate and display the total
$total = calculate_cart_total();
echo "<p><strong>Total: $" . $total . "</strong></p>";

// Remove an item
remove_from_cart(2);

//Display the updated cart
echo "<h2>Your Cart after removing the mouse</h2>";
$cart_items = get_cart_items();
if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

//Update Quantity
update_quantity(1, 3);
echo "<h2>Your Cart after updating the laptop quantity</h2>";
$cart_items = get_cart_items();
if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

?>
