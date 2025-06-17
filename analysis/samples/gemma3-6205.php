

<?php

// Initialize an empty session if it doesn't exist
session_start();

// Cart variables (This is a simplified example, replace with a database or more robust storage)
$cart = [];

// Function to add an item to the cart
function addItemToCart($productId, $productName, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$productId])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    // If not, add a new entry
    $_SESSION['cart'][$productId] = [
      'name' => $productName,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($productId, $newQuantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
    }
}

// Function to remove an item from the cart
function removeItemFromCart($productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Example usage:  This simulates a user adding items to their cart
// In a real application, this would come from a form submission or API call

// Add a product
addItemToCart(1, "Laptop", 1200, 1);

// Add another quantity of the same product
addItemToCart(1, "Laptop", 1200, 2);

// Add a different product
addItemToCart(2, "Mouse", 25, 3);

// Get the cart contents
$cartContents = getCartContents();

// Display the cart contents
echo "<h2>Your Cart</h2>";
if (empty($cartContents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartContents as $productId => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}


// Example of updating the quantity
updateQuantity(1, 5); // Change the quantity of Laptop to 5

echo "<br>";
echo "<h2>Updated Cart</h2>";
$cartContents = getCartContents();
if (empty($cartContents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartContents as $productId => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}

// Example of removing an item
removeItemFromCart(2);

echo "<br>";
echo "<h2>Cart after removing Mouse</h2>";
$cartContents = getCartContents();
if (empty($cartContents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartContents as $productId => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}

?>
