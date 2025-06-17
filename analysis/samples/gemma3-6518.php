

<?php
session_start();

// Function to add items to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart exists in session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If not, add the product to the cart
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

// Function to update the quantity of a product in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to get the cart items
function getCartItems() {
  return $_SESSION['cart'];
}

// Function to calculate the total cart value
function calculateTotal() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
  }
  return $total;
}

// --- Example Usage ---

// 1. Add an item to the cart
addToCart(1, 'T-Shirt', 20, 2); // product_id, name, price, quantity
addToCart(2, 'Jeans', 50, 1);

// 2. Display the cart contents
echo "<h2>Cart Items:</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

echo "<p><strong>Total: $" . calculateTotal() . "</strong></p>";

// 3. Remove an item from the cart
removeFromCart(1);

// 4.  Update the quantity of an item
updateQuantity(2, 3); // Increase the quantity of Jeans to 3

// 5. Display the updated cart contents
echo "<h2>Updated Cart Items:</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

echo "<p><strong>Total: $" . calculateTotal() . "</strong></p>";
?>
