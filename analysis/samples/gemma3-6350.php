

<?php
session_start(); // Start the session

// Cart initialization - Initialize an empty cart in the session.
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If yes, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If no, add the product to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Example Usage (Simulated Product Data)
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75],
];

//  Simulate user actions (Example)
// Add a laptop to the cart
addToCart(1, 'Laptop', $products[1]['price']);

// Add a mouse to the cart
addToCart(2, 'Mouse', $products[2]['price'], 2); // Add two mice

//  Add a keyboard to the cart
addToCart(3, 'Keyboard', $products[3]['price']);

// Update the quantity of the mouse
updateCartQuantity(2, 5);

// Remove the keyboard from the cart
removeCartItem(3);

// Display the cart contents
echo "<h2>Cart Contents</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " - Total: $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}

?>
