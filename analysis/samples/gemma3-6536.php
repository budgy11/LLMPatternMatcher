

<?php
session_start();

// Function to add items to the cart
function addItemToCart($productId, $productName, $price, $quantity) {
  // Check if the cart already exists
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Add the new item to the cart array
  $_SESSION['cart'][$productId] = array(
    'name' => $productName,
    'price' => $price,
    'quantity' => $quantity
  );
}

// Function to update the quantity of an item in the cart
function updateQuantity($productId, $quantity) {
  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] = $quantity;
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


// *** Example Usage (Simulated Product Info - Replace with your DB queries) ***

// Let's assume we have these products:
// Product ID: 1 - Name: "Laptop", Price: 1000,  Quantity: 1
// Product ID: 2 - Name: "Mouse", Price: 25,        Quantity: 2
// Product ID: 3 - Name: "Keyboard", Price: 75,     Quantity: 1

// 1. Adding items to the cart:
addItemToCart(1, "Laptop", 1000, 1);
addItemToCart(2, "Mouse", 25, 2);
addItemToCart(3, "Keyboard", 75, 1);



// 2. Updating the cart (e.g., increase the quantity of the mouse)
updateQuantity(2, 5); // Change the quantity of "Mouse" to 5

// 3. Removing an item from the cart:
// removeItemFromCart(1); // Remove the "Laptop" from the cart

// 4. Displaying the cart contents:
$cartContents = getCartContents();

if (empty($cartContents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<h2>Your Shopping Cart:</h2>";
  echo "<ul>";
  foreach ($cartContents as $productId => $item) {
    echo "<li>Product: " . $item['name'] . " (ID: " . $productId . ") - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
  }
  echo "</ul>";
}
?>
