

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  $_SESSION['cart'][$product_id] = array(
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


// Function to remove an item from the cart
function removeItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}


// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Function to calculate the cart total
function calculateCartTotal() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
  }
  return $total;
}

// Example Usage - Simulate adding a product to the cart

// Simulate a product ID
$product_id = 123;
$product_name = "Awesome T-Shirt";
$price = 25.00;

// Add the item to the cart
addToCart($product_id, $product_name, $price);

// Update the quantity of the item
updateQuantity($product_id, 2); // Now we have 2 of the T-Shirt

// Get the cart contents
$cart = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}


// Calculate and display the total
$total = calculateCartTotal();
echo "<p><strong>Total: $" . $total . "</strong></p>";


// Remove an item from the cart
// removeItem($product_id); // Uncomment this line to remove the T-Shirt from the cart
?>
