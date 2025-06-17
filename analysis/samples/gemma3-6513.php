

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addItemToCart($productId, $productName, $price, $quantity = 1) {
  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$productId])) {
    // If it exists, increase the quantity
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, create a new entry
    $_SESSION['cart'][$productId] = [
      'name' => $productName,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function removeItemFromCart($productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($productId, $newQuantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
    }
}


// Example usage:

// 1. Adding items to the cart
addItemToCart('product1', 'Awesome T-Shirt', 20.00, 2);
addItemToCart('product2', 'Cool Hat', 15.00);

// 2. Updating the quantity of an item
updateQuantity('product1', 3);  //Increase quantity of product1 to 3

// 3. Removing an item from the cart
//removeItemFromCart('product2');

// Display the cart contents
echo "<h2>Your Cart</h2>";
echo "<ul>";
$total = 0;

if (isset($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $productId => $item) {
    $name = $item['name'];
    $price = $item['price'];
    $quantity = $item['quantity'];
    $subtotal = $price * $quantity;

    echo "<li>" . $name . " - $" . $price . " x " . $quantity . " = $" . $subtotal . "</li>";
    $total += $subtotal;
  }
} else {
  echo "<li>Your cart is empty.</li>";
}

echo "</ul>";
echo "<p><strong>Total: $" . $total . "</strong></p>";
?>
