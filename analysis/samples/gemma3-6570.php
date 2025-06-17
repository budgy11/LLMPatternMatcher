

<?php

// Initialize the session if it's not already started
session_start();

// Array to store the items in the cart
$cart = array();

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart already has an item with the same product_id
  if (isset($cart[$product_id])) {
    // If the item exists, increment the quantity
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    // If the item doesn't exist, add it to the cart
    $cart[$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $new_quantity;
    }
}


// Example Usage:

// Add some items to the cart
add_to_cart(1, 'T-Shirt', 20, 2);
add_to_cart(2, 'Jeans', 50, 1);
add_to_cart(1, 'T-Shirt', 20, 3); // Adding more of the same item


// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>";
    echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}

// Example: Remove an item
//remove_from_cart(2);

// Example: Update Quantity
//update_quantity(1, 5); // Change the quantity of T-Shirt to 5

// Save the cart session
session_start();
$_SESSION['cart'] = $cart;  // Store the cart data in the session

// Output for testing/verification
echo "<p>Cart data saved to session.</p>";

// To retrieve the cart from the session later:
// session_start();
// $cart = $_SESSION['cart'];
// ... use $cart ...

?>
