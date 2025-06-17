

<?php
session_start(); // Start the session

// Array to hold the cart items
$cart = array();

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  global $cart; // Access the global $cart array

  // Check if the product is already in the cart
  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    $cart[$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Example usage:  Add some items to the cart
add_to_cart(1, 'T-Shirt', 20.00, 2);
add_to_cart(2, 'Jeans', 50.00, 1);
add_to_cart(1, 'T-Shirt', 20.00, 1); // Add another T-Shirt

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_details) {
    $product_name = $product_details['name'];
    $price = $product_details['price'];
    $quantity = $product_details['quantity'];

    echo "<li>" . $product_name . " - $" . $price . " x " . $quantity . " = $" . ($price * $quantity) . "</li>";
  }
  echo "</ul>";

  // Calculate the total price
  $total_price = 0;
  foreach ($cart as $product_id => $product_details) {
    $total_price += $product_details['price'] * $product_details['quantity'];
  }

  echo "<p><strong>Total: $" . $total_price . "</strong></p>";
}

// Function to remove an item from the cart (Optional - for demonstration)
function remove_from_cart($product_id) {
  global $cart;

  if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
  }
}

// Example: Remove a T-Shirt
// remove_from_cart(1); //Remove T-Shirt with ID 1

//  You'll likely have more complex logic here, like:
//  - Handling quantity updates
//  - Saving the cart data to a database or session storage
//  - Integrating with your product catalog
?>
