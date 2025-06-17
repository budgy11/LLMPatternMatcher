

<?php

session_start(); // Start the session

// Define a function to add items to the cart
function addItemToCart($productId, $productName, $price, $quantity) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$productId])) {
    // If the product exists, increment the quantity
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    // If the product doesn't exist, add it to the cart
    $_SESSION['cart'][$productId] = [
      'name' => $productName,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Example usage (Simulating adding items to the cart)
addItemToCart(1, 'T-Shirt', 20, 2);
addItemToCart(2, 'Jeans', 50, 1);
addItemToCart(1, 'T-Shirt', 20, 3); // Add more of the T-Shirt

// Display the contents of the cart
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $productId => $item) {
    echo "<li>";
    echo "Product: " . $item['name'] . "<br>";
    echo "Price: $" . number_format($item['price'], 2) . "<br>";
    echo "Quantity: " . $item['quantity'] . "<br>";
    echo "Total for this item: $" . number_format($item['price'] * $item['quantity'], 2) . "</li>";
  }
  echo "</ul>";
}

// Calculate the total cost
$totalCost = 0;
if (!empty($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $productId => $item) {
    $totalCost += $item['price'] * $item['quantity'];
  }
}

echo "<p><strong>Total Cost: $" . number_format($totalCost, 2) . "</strong></p>";

// --- Example of removing an item (optional) ---
// To remove an item, you would use a function like this:
// function removeItemFromCart($productId) {
//   if (isset($_SESSION['cart'][$productId])) {
//     unset($_SESSION['cart'][$productId]);
//   }
// }
//
// // Example: Remove product ID 1
// removeItemFromCart(1);

?>
