

<?php
session_start();

// Function to add an item to the cart
function addToCart($productId, $quantity = 1, $productName = null) {
  // Check if the cart already exists in session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$productId])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add a new item to the cart
    $_SESSION['cart'][$productId] = array(
      'quantity' => $quantity,
      'productName' => $productName ?? $productId // Use productName if provided, otherwise use the product ID
    );
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($productId, $quantity) {
  // Check if the cart exists
  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] = $quantity;
  } else {
    // If the product isn't in the cart, do nothing (or perhaps error)
    //  Consider logging an error if this is unexpected
    //  echo "Product ID " . $productId . " not found in cart.";
  }
}

// Function to remove an item from the cart
function removeCartItem($productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Function to clear the cart
function clearCart() {
  unset($_SESSION['cart']);
}

// ------------------------------------------------------------------
//  Example Usage (Simulating user interaction)
// ------------------------------------------------------------------

// Add an item to the cart
addToCart(123, 2); // Product ID 123, quantity 2
addToCart(456, 1, "Awesome Widget"); // Product ID 456, quantity 1, with product name
addToCart(123, 3); // add more of product 123


// Display the cart contents
echo "<h2>Cart Contents:</h2>";
$cart = getCartContents();

if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $productId => $item) {
    echo "<li>Product ID: " . $productId . ", Quantity: " . $item['quantity'] . ", Name: " . $item['productName'] . "</li>";
  }
  echo "</ul>";
}


// Simulate updating the quantity of product 123
updateQuantity(123, 5);

echo "<h2>Cart Contents After Update:</h2>";
$cart = getCartContents();
echo "<ul>";
foreach ($cart as $productId => $item) {
  echo "<li>Product ID: " . $productId . ", Quantity: " . $item['quantity'] . ", Name: " . $item['productName'] . "</li>";
}
echo "</ul>";

// Remove an item
removeCartItem(456);

// Display cart after removal
echo "<h2>Cart Contents After Removal:</h2>";
$cart = getCartContents();
echo "<ul>";
foreach ($cart as $productId => $item) {
  echo "<li>Product ID: " . $productId . ", Quantity: " . $item['quantity'] . ", Name: " . $item['productName'] . "</li>";
}
echo "</ul>";


// Clear the cart
//clearCart();
//echo "<h2>Cart Contents After Clearing:</h2>";
// $cart = getCartContents();
// echo "<ul>";
// foreach ($cart as $productId => $item) {
//   echo "<li>Product ID: " . $productId . ", Quantity: " . $item['quantity'] . ", Name: " . $item['productName'] . "</li>";
// }
// echo "</ul>";
?>
