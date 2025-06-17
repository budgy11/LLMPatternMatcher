

<?php
session_start();

// Function to add an item to the cart
function addToCart($productId, $productName, $price, $quantity = 1) {
  // Check if the cart exists in session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$productId])) {
    // If the product exists, increase the quantity
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    // If the product is not in the cart, add it with quantity 1
    $_SESSION['cart'][$productId] = [
      'name' => $productName,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function removeFromCart($productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($productId, $newQuantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
    }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'] ?? []; // Return empty array if cart doesn't exist
}


// Example Usage:  (This would typically come from a form submission)
// Assuming a product ID of 1, name "Laptop", price $1000, and quantity of 2

// Add 2 laptops to the cart
addToCart(1, "Laptop", 1000, 2);

// Remove the laptop (product ID 1)
// removeFromCart(1);

// Update the quantity of laptop product ID 1 to 3
// updateQuantity(1, 3);

// Display the cart contents
$cart = getCartContents();

echo "<h2>Your Shopping Cart</h2>";

if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $productId => $item) {
    echo "<li>";
    echo "Product: " . $item['name'] . "<br>";
    echo "Price: $" . $item['price'] . "<br>";
    echo "Quantity: " . $item['quantity'] . "<br>";
    echo "Total for this item: $" . ($item['price'] * $item['quantity']) . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

echo "<p>Total Cart Value: " . number_format(calculateCartTotal($cart)) . "</p>";
?>
