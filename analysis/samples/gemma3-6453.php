

<?php

// Initialize the session (if it doesn't exist)
session_start();

// Cart array (can be more sophisticated - database, etc.)
$cart = [];

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($cart[$product_id])) {
    // Increment the quantity if the product exists
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $cart[$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $new_quantity) {
  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] = $new_quantity;
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  unset($cart[$product_id]);
}

// Function to get the cart contents
function get_cart() {
  return $cart;
}

// --- Example Usage (Simulated Product Data) ---

// Add some products to the cart
add_to_cart(1, 'Laptop', 1200, 1);
add_to_cart(2, 'Mouse', 25, 2);
add_to_cart(1, 'Laptop', 1200, 1); // Add another Laptop
add_to_cart(3, 'Keyboard', 75, 1);


// --- Get and Display the Cart Contents ---

$cart_items = get_cart();

// Calculate total price
$total_price = 0;
foreach ($cart_items as $item_id => $item_data) {
  $total_price += $item_data['price'] * $item_data['quantity'];
}

echo "<h2>Your Shopping Cart</h2>";

if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $item_id => $item_data) {
    echo "<li>" . $item_data['name'] . " - $" . $item_data['price'] . " x " . $item_data['quantity'] . " = $" . $item_data['price'] * $item_data['quantity'] . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . $total_price . "</strong></p>";
}


// --- Example Session Data Handling (Optional - for persistence) ---
// This is to illustrate how you could save the cart data to the session.
// In a real application, you'd likely save this to a database.

//  You can add/remove items from the session using these functions
//  add_to_cart($product_id, $product_name, $price, $quantity);
//  update_cart_quantity($product_id, $new_quantity);
//  remove_from_cart($product_id);

// After a user adds items, you might do something like this:
// $_SESSION['cart'] = get_cart();
// session_start(); // Always needed after modifying session variables
?>
