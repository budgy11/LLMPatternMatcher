

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// --- Example Functions for Cart Operations ---

// Add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment the quantity if the product exists
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  } else {
    // If the product is not in the cart, it's an error
    return false;
  }
  return true;
}

// Remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Get all items in the cart
function get_cart_items() {
  return $_SESSION['cart'];
}

// Calculate the cart total
function calculate_cart_total() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
  }
  return $total;
}


// --- Example Usage (Simulating User Actions) ---

// Add some items to the cart
add_to_cart(1, "Laptop", 1200, 1);
add_to_cart(2, "Mouse", 25, 2);
add_to_cart(1, "Laptop", 1200, 1); // Add another laptop

// Update quantity of the mouse
update_cart_quantity(2, 5);

// Remove the mouse
remove_from_cart(2);

// Get the cart contents
$cart_items = get_cart_items();
echo "<h2>Cart Items:</h2>";
echo "<ul>";
foreach ($cart_items as $item) {
  echo "<li>" . $item['name'] . " - " . $item['quantity'] . " x $" . $item['price'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
}
echo "</ul>";

// Calculate and display the total
$total = calculate_cart_total();
echo "<p><strong>Total: $" . $total . "</strong></p>";

?>
