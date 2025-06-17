

<?php
session_start();

// ------------------------------------------------------------------
//  Example Cart Session Functions
// ------------------------------------------------------------------

// Function to add an item to the cart
function addToCart($product_id, $quantity, $product_name = 'Unknown Product', $product_price = 0.00) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  $_SESSION['cart'][$product_id] = array(
    'name' => $product_name,
    'quantity' => $quantity,
    'price' => $product_price
  );
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart total
function getCartTotal() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['quantity'] * $item['price'];
    }
  }
  return $total;
}

// Function to get the cart contents
function getCartContents() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  }
  return null;
}



// ------------------------------------------------------------------
//  Example Usage (simulated shopping process)
// ------------------------------------------------------------------

// Simulate user actions (e.g., from a form submission)

// 1. Add some items to the cart
addToCart(101, 2, 'T-Shirt', 20.00);
addToCart(102, 1, 'Jeans', 50.00);
addToCart(103, 3, 'Hat', 15.00);

// 2. User updates the quantity of a T-Shirt
updateCartQuantity(101, 5);

// 3. User removes Jeans
removeFromCart(102);

// 4. Get the cart contents
$cart = getCartContents();
if ($cart) {
  echo "<h2>Cart Contents:</h2>";
  echo "<ul>";
  foreach ($cart as $item_id => $item_data) {
    echo "<li>" . $item_data['name'] . " - Quantity: " . $item_data['quantity'] . " - Price: $" . $item_data['price'] . "</li>";
  }
  echo "</ul>";
  echo "<h2>Total: $" . getCartTotal() . "</h2>";
} else {
  echo "<p>Your cart is empty.</p>";
}

?>
