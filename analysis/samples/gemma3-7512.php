

<?php

// Sample Product Data (Replace with your database connection)
$products = [
  1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
  2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
  3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// Session Management (For maintaining cart items)
session_start();

// Initialize the cart in the session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($productId, $quantity = 1, $cart) {
  if (!is_numeric($productId) || $productId <= 0) {
    return false; // Invalid product ID
  }

  if (!is_numeric($quantity) || $quantity <= 0) {
    return false; // Invalid quantity
  }

  if (!array_key_exists($productId, $products)) {
    return false; // Product not found
  }

  if (isset($cart[$productId])) {
    $cart[$productId] = $cart[$productId] + $quantity;
  } else {
    $cart[$productId] = $quantity;
  }
  return true;
}

// Function to remove an item from the cart
function removeFromCart($productId, $cart) {
  if (!is_numeric($productId) || $productId <= 0) {
    return false; // Invalid product ID
  }

  if (isset($cart[$productId])) {
    unset($cart[$productId]);
  }
  return true;
}

// Function to get the cart total
function calculateCartTotal($cart) {
  $total = 0;
  foreach ($cart as $productId => $quantity) {
    if (array_key_exists($productId, $products)) {
      $total += $products[$productId]['price'] * $quantity;
    }
  }
  return $total;
}

// Handle Form Submission (Adding to Cart)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productId = (int)$_POST['product_id']; // Convert to integer
    $quantity = (int)$_POST['quantity'];

    if (addToCart($productId, $quantity, $_SESSION['cart'])) {
      echo "<p>Item added to cart!</p>";
    } else {
      echo "<p style='color:red;'>Error adding item to cart.</p>";
    }
  }
}

// Handle Form Submission (Removing from Cart)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_product_id'])) {
  $removeProductId = (int)$_POST['remove_product_id'];
  removeFromCart($removeProductId, $_SESSION['cart']);
  echo "<p>Item removed from cart!</p>";
}


// Display Cart Contents
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $productId => $quantity) {
    echo "<li>" . $products[$productId]['name'] . " - Quantity: " . $quantity . " - Price: $" . $products[$productId]['price'] . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . calculateCartTotal($_SESSION['cart']) . "</strong></p>";
}

?>
