

<?php
session_start();

// --- Example Cart Data ---
// In a real application, this would likely come from a database or API.
$cart = [];

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  global $cart;

  if (isset($cart[$product_id])) {
    // Item already in cart, increment quantity
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    // Add new item to cart
    $cart[$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  global $cart;
  if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
    global $cart;
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $new_quantity;
    }
}

// Function to get the cart total
function get_cart_total($cart) {
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// --- Example Usage ---

// 1. Add items to the cart
add_to_cart(1, 'T-Shirt', 20.00, 2);
add_to_cart(2, 'Jeans', 50.00, 1);
add_to_cart(1, 'T-Shirt', 20.00, 1);  // Add another T-Shirt
// 2.  Retrieve Cart Contents (for display - this is just a demo)
echo "<h2>Your Cart</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
}


// 3. Get and display the cart total
$total = get_cart_total($cart);
echo "<p><strong>Total: $" . $total . "</strong></p>";

// 4.  Simulating a removal (Example)
// remove_from_cart(2);
// echo "<p>Jeans have been removed from your cart.</p>";


// 5.  Update Quantity (Example)
// update_quantity(1, 3);
// echo "<p>You've updated the quantity of T-Shirts to 3.</p>";
?>
