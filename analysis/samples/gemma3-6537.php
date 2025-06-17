

<?php
session_start(); // Start the session

// Function to add an item to the cart
function addToCart($product_id, $quantity, $product_name, $price) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  $_SESSION['cart'][$product_id] = array(
    'name' => $product_name,
    'quantity' => $quantity,
    'price' => $price
  );
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart contents
function getCartContents() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  } else {
    return array();
  }
}

// Example Usage (simulating a form submission)

// Assume this comes from a form submission with product_id, quantity, etc.
// For demonstration purposes, let's hardcode some data:
$product_id = 1;
$quantity = 2;
$product_name = "Awesome T-Shirt";
$price = 25.00;

// Add the item to the cart
addToCart($product_id, $quantity, $product_name, $price);

// Display the cart contents
$cart = getCartContents();

echo "<h2>Your Shopping Cart</h2>";

if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $item_id => $item_data) {
    echo "<li>" . $item_data['name'] . " - Quantity: " . $item_data['quantity'] . " - Price: $" . $item_data['price'] . "</li>";
  }
  echo "</ul>";

  //  Example: Update quantity (using a hypothetical form input)
  //  Assume the user changed the quantity of item 1 to 5
  //  updateCartQuantity(1, 5);
  //  echo "<ul>";
  //  foreach ($cart as $item_id => $item_data) {
  //    echo "<li>" . $item_data['name'] . " - Quantity: " . $item_data['quantity'] . " - Price: $" . $item_data['price'] . "</li>";
  //  }
  //  echo "</ul>";

  // Example: Remove an item
  //  removeCartItem(1);
  //  echo "<ul>";
  //  foreach ($cart as $item_id => $item_data) {
  //    echo "<li>" . $item_data['name'] . " - Quantity: " . $item_data['quantity'] . " - Price: $" . $item_data['price'] . "</li>";
  //  }
  //  echo "</ul>";
}

?>
