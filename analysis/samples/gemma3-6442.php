

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $quantity, $product_name, $price) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'name' => $product_name,
      'price' => $price
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
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}


// Example Usage (Simulating user interaction)

// Add a product to the cart
addToCart(1, 2, "Awesome T-Shirt", 20.00);
addToCart(2, 1, "Cool Hat", 15.00);

// Display the cart contents
echo "<h2>Your Cart</h2>";
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $id => $item) {
    echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
  }
  echo "</ul>";

  // Calculate the total price
  $total = 0;
  foreach ($_SESSION['cart'] as $id => $item) {
    $total += $item['quantity'] * $item['price'];
  }
  echo "<p><strong>Total: $" . number_format($total, 2) . "</strong></p>";

  // Remove an item from the cart (example)
  removeFromCart(2);
} else {
  echo "<p>Your cart is empty.</p>";
}

// Update quantity (example)
updateQuantity(1, 3);

// Display updated cart
echo "<h2>Your Cart (Updated)</h2>";
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $id => $item) {
        echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
}

?>
