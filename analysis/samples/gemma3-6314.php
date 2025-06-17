

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $quantity, $product_name, $price) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product not in cart, add it
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'name'     => $product_name,
      'price'    => $price
    ];
  }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'] ?? []; // Return empty array if cart is empty
}

// Example usage (Demonstration - Replace with your actual data retrieval)
//  Simulating product data for demonstration.  In a real application, this would
//  come from a database or other source.

$products = [
  1 => ['name' => 'Laptop', 'price' => 1200],
  2 => ['name' => 'Mouse', 'price' => 25],
  3 => ['name' => 'Keyboard', 'price' => 75],
];

//  Simulating user interaction.  This is just for demonstrating the cart functionality.
//  In a real website, you'd get this data from user actions (add to cart button, etc.).

// Add a laptop to the cart
addToCart(1, 1, $products[1]['name'], $products[1]['price']);

// Add a mouse to the cart
addToCart(2, 2, $products[2]['name'], $products[2]['price']);

// Update the quantity of the mouse
updateCartQuantity(2, 5);

// Get the cart contents
$cart = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
  }
  echo "</ul>";
}
?>
