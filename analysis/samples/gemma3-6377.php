

<?php

// Start a session if it doesn't exist
session_start();

// --- Example Cart Data (Replace with a database or other storage) ---
$cart = []; // Array to store items in the cart

// Function to add an item to the cart
function addToCart($item_id, $item_name, $price, $quantity = 1) {
  // Check if the item is already in the cart
  if (isset($cart[$item_id])) {
    // Item exists, increment quantity
    $cart[$item_id]['quantity'] += $quantity;
  } else {
    // Item doesn't exist, add it to the cart
    $cart[$item_id] = [
      'name' => $item_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function removeItemFromCart($item_id) {
    unset($cart[$item_id]);
}

// Function to update quantity of an item in the cart
function updateQuantity($item_id, $newQuantity) {
  if (isset($cart[$item_id])) {
    $cart[$item_id]['quantity'] = $newQuantity;
  }
}

// --- Example Usage/Handling (Simulation of user actions) ---

// User adds an item
if (isset($_POST['add_to_cart'])) {
  $item_id = $_POST['item_id'];
  $item_name = $_POST['item_name'];
  $price = $_POST['price'];
  $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; // Default quantity is 1

  addToCart($item_id, $item_name, $price, $quantity);
  // You could add a success message or redirect after adding
}


// User removes an item
if (isset($_POST['remove_item'])) {
  $item_id = $_POST['item_id'];
  removeItemFromCart($item_id);
}

// User updates quantity
if (isset($_POST['update_quantity'])) {
  $item_id = $_POST['item_id'];
  $newQuantity = $_POST['quantity'];
  updateQuantity($item_id, $newQuantity);
}


// --- Displaying the Cart ---

//  Get the cart contents for display (This can be done from session storage)
$cart_items = [];
if (isset($_SESSION['cart'])) {
  $cart_items = $_SESSION['cart'];
}


echo "<h2>Your Shopping Cart</h2>";

if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $item_id => $item_data) {
    echo "<li>";
    echo "<strong>Name:</strong> " . $item_data['name'] . "<br>";
    echo "<strong>Price:</strong> $" . number_format($item_data['price'], 2) . "<br>";
    echo "<strong>Quantity:</strong> " . $item_data['quantity'] . "<br>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='item_id' value='" . $item_id . "'>";
    echo "<input type='submit' value='Remove Item'>";
    echo "</form>";
    echo "</li>";
  }
  echo "</ul>";
}


?>
