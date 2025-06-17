

<?php
session_start();

// Sample product data (replace with your actual data source)
$products = [
  1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
  2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
  3 => ['id' => 3, 'name' => 'Sneakers', 'price' => 80],
];


// Function to add an item to the cart
function addToCart($productId, $quantity = 1)
{
  if (isset($_SESSION['cart'][$productId])) {
    // Item already in cart, increment quantity
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    // Item not in cart, add it with quantity 1
    $_SESSION['cart'][$productId] = [
      'id' => $productId,
      'name' => $products[$productId]['name'],
      'price' => $products[$productId]['price'],
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function removeFromCart($productId)
{
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}


// --- Example Usage (This would typically come from a form submission) ---

// Add some items to the cart
addToCart(1, 2); // Add 2 T-Shirts
addToCart(2, 1); // Add 1 pair of Jeans
// addToCart(3, 3); // Add 3 pairs of Sneakers

// Display the cart contents
echo "<h2>Your Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $itemId => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";

  // Example: Remove an item (replace with the ID you want to remove)
  // removeFromCart(1);
}


?>
