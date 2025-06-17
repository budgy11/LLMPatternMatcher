

<?php

session_start(); // Start the session

// Example function to add items to the cart
function addToCart($item_id, $item_name, $price, $quantity = 1) {
  // Check if the cart exists in session.
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the item already exists in the cart
  if (isset($_SESSION['cart'][$item_id])) {
    // If item exists, increment quantity
    $_SESSION['cart'][$item_id]['quantity'] += $quantity;
  } else {
    // If item doesn't exist, add it to the cart
    $_SESSION['cart'][$item_id] = [
      'name' => $item_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Example function to update cart quantity
function updateCartQuantity($item_id, $new_quantity) {
  if (isset($_SESSION['cart'][$item_id])) {
    $_SESSION['cart'][$item_id]['quantity'] = $new_quantity;
  }
}

// Example function to remove an item from the cart
function removeFromCart($item_id) {
  if (isset($_SESSION['cart'][$item_id])) {
    unset($_SESSION['cart'][$item_id]);
  }
}

// Example function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}


// ----  Example Usage  ----

// Add some items to the cart
addToCart(1, 'T-Shirt', 20, 2);
addToCart(2, 'Jeans', 50, 1);
addToCart(1, 'T-Shirt', 20, 3); // Add more of the same item


// Update quantity
updateCartQuantity(1, 5); // Increase quantity of item 1 to 5

// Get the cart contents
$cart_contents = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $item_id => $item_data) {
    echo "<li>" . $item_data['name'] . " - $" . $item_data['price'] . " x " . $item_data['quantity'] . " = $" . ($item_data['price'] * $item_data['quantity']) . "</li>";
  }
  echo "</ul>";
}


// Remove an item
removeFromCart(2);

// Get the cart contents again to see the change
$cart_contents = getCartContents();
echo "<br><h2>Cart Contents after removing item 2:</h2>";
if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $item_id => $item_data) {
    echo "<li>" . $item_data['name'] . " - $" . $item_data['price'] . " x " . $item_data['quantity'] . " = $" . ($item_data['price'] * $item_data['quantity']) . "</li>";
  }
  echo "</ul>";
}

?>
