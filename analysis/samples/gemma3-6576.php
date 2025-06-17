

<?php
session_start();

// Simulate a shopping cart - replace with your actual database or data structure
$cart = [];

// Function to add an item to the cart
function add_to_cart($item_id, $item_name, $price, $quantity = 1) {
  global $cart;

  if (isset($cart[$item_id])) {
    // Item already exists in cart, increment quantity
    $cart[$item_id]['quantity'] += $quantity;
  } else {
    // Item doesn't exist, add a new entry
    $cart[$item_id] = [
      'name' => $item_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function remove_from_cart($item_id) {
  global $cart;

  if (isset($cart[$item_id])) {
    unset($cart[$item_id]);
  }
}

// Function to update the quantity of an item in the cart
function update_quantity($item_id, $new_quantity) {
    global $cart;

    if (isset($cart[$item_id])) {
        $cart[$item_id]['quantity'] = $new_quantity;
    }
}

// Example usage:

// Add some items to the cart
add_to_cart(1, 'T-Shirt', 20, 2);
add_to_cart(2, 'Jeans', 50, 1);
add_to_cart(1, 'T-Shirt', 20, 1);  // Add another T-Shirt

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $item_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}

// Example of removing an item
//remove_from_cart(2);

// Example of updating the quantity of an item
// update_quantity(1, 5);



// Example of session variable usage
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = $cart; // Store the cart data in the session
} else {
    //Ensure cart data is always the same
    $_SESSION['cart'] = $cart;
}


// Display the cart contents (again) - using session data
echo "<h2>Shopping Cart (from Session)</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}



?>
