

<?php
session_start();

// Session variables to store items in the cart
$cart = array(); 

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  global $cart;

  if (isset($cart[$product_id])) {
    // Item already in cart, increase quantity
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    // Item not in cart, add it
    $cart[$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
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
function update_quantity($product_id, $quantity) {
  global $cart;

  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] = $quantity;
  }
}

// Function to get the cart contents
function get_cart_contents() {
  return $cart;
}

// Example usage (simulated products)

// Add some items to the cart
add_to_cart(1, "Laptop", 1200, 1);
add_to_cart(2, "Mouse", 25, 2);
add_to_cart(1, "Laptop", 1200, 1);  // Add another laptop
add_to_cart(3, "Keyboard", 75, 1);

// Display the cart contents
$cart_items = get_cart_contents();

echo "<h2>Your Shopping Cart</h2>";
if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $product_id => $item) {
    echo "<li>" . $item['name'] . " - Price: $" . $item['price'] . " - Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

//Example of removing an item
//remove_from_cart(2);

//Example of updating the quantity of an item
//update_quantity(1, 3);


?>
