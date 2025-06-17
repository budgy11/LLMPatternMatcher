

<?php
session_start(); // Start the session

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize empty cart array
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increase quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
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


// *** Example Usage - Simulate adding an item to the cart ***
// Assuming we have product data (replace with your actual data source)

$products = [
  1 => ['name' => 'T-Shirt', 'price' => 20],
  2 => ['name' => 'Jeans', 'price' => 50],
  3 => ['name' => 'Shoes', 'price' => 80]
];

// Example 1: Add a T-Shirt to the cart
addToCart(1, $products[1]['name'], $products[1]['price'], 2);

// Example 2: Add Jeans to the cart
addToCart(2, $products[2]['name'], $products[2]['price']);

// Example 3: Update the quantity of the T-Shirt to 3
updateQuantity(1, 3);


// Display the cart contents
$cart = getCartContents();

echo "<h2>Your Cart</h2>";

if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_details) {
    echo "<li>" . $product_details['name'] . " - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
  }
  echo "</ul>";
}

?>
