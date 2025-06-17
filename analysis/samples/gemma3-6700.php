

<?php

// Start a session (if not already started)
session_start();

// Cart data (This would typically come from a database or file)
$cart = [];

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
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
function remove_from_cart($product_id) {
  unset($cart[$product_id]);
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $new_quantity;
    }
}


// Example usage (Simulating user interaction)

// Add some items to the cart
add_to_cart(1, 'T-Shirt', 20, 2);
add_to_cart(2, 'Jeans', 50, 1);
add_to_cart(1, 'T-Shirt', 20, 3); // Add more of the T-Shirt

// Display the cart contents
echo "<h2>Your Cart</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $id => $item) {
    echo "<li>";
    echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}


// Example: Remove an item
// remove_from_cart(2);

// Example: Update quantity
// update_quantity(1, 5);

//  (You would typically have logic here to process the cart data,
//   e.g., calculate the total price, store the cart data in a session,
//   or pass it to a separate processing script.)


//  To persist the cart data across multiple pages, you'd need to:
//  1. Store the cart data in a session.
//  2. Pass the session ID to each page that needs to access the cart.
//  3.  On each page, use session_start() to start the session.
//     Then, use session_id() to get the session ID and session_start($session_id).
//     You can then access the cart data using session_get_cookie_params() and session_regenerate_id() to ensure security.
//   This is outside the scope of this basic example.

?>
