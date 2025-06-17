

<?php
session_start();

// --- Example Cart Logic ---

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it's already in the cart, increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If not, add it to the cart
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

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Function to calculate the cart total
function calculateCartTotal() {
  $total = 0;
  if (empty($_SESSION['cart'])) {
    return $total = 0;
  }
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// --- Example Usage (simulating user interaction) ---

// Add an item to the cart
addToCart('product1', 'Awesome T-Shirt', 20, 2);
addToCart('product2', 'Cool Mug', 10, 1);

// Get the cart contents
$cart = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

// Calculate and display the total
$total = calculateCartTotal();
echo "<p><strong>Total: $" . $total . "</strong></p>";

// Remove an item from the cart
removeFromCart('product1');

// Update the quantity of an item
updateQuantity('product2', 3);

// Display the cart contents after changes
$cart = getCartContents();
echo "<h2>Cart Contents After Changes:</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
}
?>
