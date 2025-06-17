

<?php

// Assuming you have a database connection established (e.g., $conn)

// Session handling - initialize if not already
session_start();

// Cart data - initially empty
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, update quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add a new entry
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// --- Example Usage (Simulating User Interaction) ---

// 1.  Add an item to the cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $product_name = $_POST['product_name'];
  $price = $_POST['price'];
  $quantity = $_POST['quantity']; // optional, default to 1

  addToCart($product_id, $product_name, $price, $quantity);
  echo "<p>Item added to cart.</p>";
}


// 2.  Update the quantity of an item
if (isset($_POST['update_quantity'])) {
  $product_id = $_POST['product_id'];
  $new_quantity = $_POST['quantity'];

  updateQuantity($product_id, $new_quantity);
  echo "<p>Quantity updated.</p>";
}

// 3.  Remove an item from the cart
if (isset($_POST['remove_item'])) {
  $product_id = $_POST['product_id'];
  removeFromCart($product_id);
  echo "<p>Item removed from cart.</p>";
}


// 4.  Display the cart contents (for demonstration)
$cart_contents = getCartContents();

if (!empty($cart_contents)) {
  echo "<h2>Your Cart</h2>";
  echo "<ul>";
  foreach ($cart_contents as $product_id => $product_data) {
    echo "<li>" . $product_data['name'] . " - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
  }
  echo "</ul>";
} else {
  echo "<p>Your cart is empty.</p>";
}

?>
