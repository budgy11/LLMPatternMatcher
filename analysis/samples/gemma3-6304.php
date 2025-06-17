

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $quantity) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = array($product_id => $quantity);
  } else {
    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id] += $quantity;
    } else {
      $_SESSION['cart'][$product_id] = $quantity;
    }
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
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

// Example usage (simulated product data - replace with your database)
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75]
];

// Simulate a user adding items to the cart
addToCart(1, 1);  // Add 1 Laptop to the cart
addToCart(2, 3);  // Add 3 Mouses to the cart
addToCart(1, 2); // Add 2 more Laptops
removeFromCart(2); // Remove all mouses

// Display the cart contents
echo "<h2>Your Cart:</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $product_name = $products[$product_id]['name'];
    $product_price = $products[$product_id]['price'];
    $total_price = $product_price * $quantity;

    echo "<li>" . $product_name . " - Quantity: " . $quantity . " - Total: $" . $total_price . "</li>";
  }
  echo "</ul>";
}

?>
