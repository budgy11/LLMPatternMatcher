

<?php

// Start a session (if it doesn't exist)
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, update the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to get the cart total
function getCartTotal() {
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// Example usage (simulating user actions)

// Add a product to the cart
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);

// Print the current cart contents
echo "<h2>Cart Contents:</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $id => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";
}

// Get the cart total
$total = getCartTotal();
echo "<p><strong>Total: $" . $total . "</strong></p>";

// Remove a product from the cart
removeFromCart(2);

// Print the updated cart contents
echo "<hr>";
echo "<h2>Cart Contents after removing Mouse:</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $id => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";
}

//Update quantity
updateQuantity(1,3);

echo "<hr>";
echo "<h2>Cart Contents after updating Laptop quantity to 3:</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $id => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";
}
?>
