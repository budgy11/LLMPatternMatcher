

<?php

session_start(); // Start the session

// Session variables to store cart data
$cart = array();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  global $cart;

  if (isset($cart[$product_id])) {
    // Item already in cart, increase quantity
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    // Add new item to cart
    $cart[$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Example usage (simulating user actions)

// Add a product to the cart
addToCart(1, "Laptop", 1200, 1);

// Add another item to the cart
addToCart(2, "Mouse", 25, 2);

// Add one more of the laptop
addToCart(1, "Laptop", 1200, 1);


// Function to display the cart contents
function displayCart() {
  global $cart;

  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Cart</h2>";
  echo "<ul>";

  foreach ($cart as $product_id => $product_details) {
    $name = $product_details['name'];
    $price = $product_details['price'];
    $quantity = $product_details['quantity'];
    $total_price = $price * $quantity;

    echo "<li>" . $name . " - $" . $price . " x " . $quantity . " = $" . $total_price . "</li>";
  }

  echo "</ul>";

  // Calculate total cart value
  $total = 0;
  foreach ($cart as $product_id => $product_details) {
    $total += $product_details['price'] * $product_details['quantity'];
  }
  echo "<p><b>Total Cart Value: $" . $total . "</b></p>";
}


// Display the cart
displayCart();

// Optional:  Clear the cart after the user is done
// session_destroy();

?>
