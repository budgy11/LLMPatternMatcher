

<?php

session_start(); // Start the session

// Session variables for the cart
$cart = [];

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
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

// Function to remove an item from the cart
function removeFromCart($product_id) {
  global $cart;
  if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    global $cart;
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $new_quantity;
    }
}

// Function to get the cart contents
function getCartContents() {
  return $cart;
}

// Function to calculate the total cart value
function calculateTotal() {
  $total = 0;
  foreach ($cart as $item) {
    $total_item_value = $item['price'] * $item['quantity'];
    $total += $total_item_value;
  }
  return $total;
}


// *** Example Usage (Illustrative - Replace with your actual product data) ***

// Add some items to the cart
addToCart(1, 'Laptop', 1200, 1);
addToCart(2, 'Mouse', 25, 2);
addToCart(1, 'Laptop', 1200, 1); // Add another Laptop - demonstrates handling duplicates

// Display the cart contents
echo "<h2>Your Cart</h2>";
echo "<ul>";
$cart_contents = getCartContents();

foreach ($cart_contents as $product_id => $item) {
  echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
}

echo "</ul>";

echo "<p><strong>Total: $" . calculateTotal() . "</strong></p>";

// Example: Remove an item
// removeFromCart(2);

// Example: Update quantity
// updateQuantity(1, 3);


?>
