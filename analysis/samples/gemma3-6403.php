

<?php
session_start();

// Function to add an item to the cart
function addItemToCart($itemId, $itemName, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the item already exists in the cart
  if (isset($_SESSION['cart'][$itemId])) {
    $_SESSION['cart'][$itemId]['quantity'] += $quantity;
  } else {
    // If not, add the item to the cart
    $_SESSION['cart'][$itemId] = [
      'id' => $itemId,
      'name' => $itemName,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($itemId, $newQuantity) {
    if (isset($_SESSION['cart'][$itemId])) {
        $_SESSION['cart'][$itemId]['quantity'] = $newQuantity;
    }
}

// Function to remove an item from the cart
function removeItemFromCart($itemId) {
  if (isset($_SESSION['cart'][$itemId])) {
    unset($_SESSION['cart'][$itemId]);
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// ------------------------------------------------------
// Example Usage (Illustrative - Replace with your actual logic)
// ------------------------------------------------------

// Example: Add an item to the cart
addItemToCart(1, "Laptop", 1200, 1);
addItemToCart(2, "Mouse", 25, 2);

// Get the cart contents
$cart = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $itemId => $item) {
      echo "<li>" . $item['name'] . " - $" . $item['price'] . " (Quantity: " . $item['quantity'] . ")</li>";
    }
    echo "</ul>";
}


// Example: Update quantity
updateQuantity(2, 3);

// Get the cart contents again to see the update
$cart = getCartContents();
echo "<h2>Cart Contents (After Update):</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $itemId => $item) {
      echo "<li>" . $item['name'] . " - $" . $item['price'] . " (Quantity: " . $item['quantity'] . ")</li>";
    }
    echo "</ul>";
}

// Example: Remove an item from the cart
removeItemFromCart(1);

// Get the cart contents again
$cart = getCartContents();
echo "<h2>Cart Contents (After Removal):</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $itemId => $item) {
      echo "<li>" . $item['name'] . " - $" . $item['price'] . " (Quantity: " . $item['quantity'] . ")</li>";
    }
    echo "</ul>";
}
?>
