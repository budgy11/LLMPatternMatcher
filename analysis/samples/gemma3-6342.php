

<?php
session_start();

// Cart data (in a real application, this would likely come from a database)
$cart = [];

// Helper functions for cart operations
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  global $cart;

  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    $cart[$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

function update_quantity($product_id, $new_quantity) {
  global $cart;

  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] = $new_quantity;
  }
}

function remove_from_cart($product_id) {
  global $cart;
  unset($cart[$product_id]);
}

function get_cart_total() {
  global $cart;
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

function get_cart_items() {
    global $cart;
    return $cart;
}

// --- Example Usage (Simulating User Actions) ---

// 1. User adds a product
add_to_cart(1, 'T-Shirt', 20, 2); // Product ID 1: T-Shirt, Quantity: 2
add_to_cart(2, 'Jeans', 50, 1);  // Product ID 2: Jeans

// 2. User updates the quantity of a product
update_quantity(1, 3); // Increase T-Shirt quantity to 3

// 3. User removes a product
//remove_from_cart(2); // Remove Jeans

// 4.  Get the cart contents for display
$cart_items = get_cart_items();

// 5. Get the cart total
$total = get_cart_total();

// --- Displaying the Cart (For demonstration) ---
?>
