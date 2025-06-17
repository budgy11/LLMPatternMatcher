

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart exists, initialize if not
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product already exists, increase quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product not in cart, add it
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'] ?? []; // Return cart or an empty array if it doesn't exist
}

// Example Usage (Simulating product data)
$products = [
  1 => ['name' => 'T-Shirt', 'price' => 20],
  2 => ['name' => 'Jeans', 'price' => 50],
  3 => ['name' => 'Hat', 'price' => 15],
];


// 1. Add an item to the cart
addToCart(1, $products[1]['name'], $products[1]['price'], 2); // Add 2 T-Shirts

// 2. Update quantity of an item
updateCartQuantity(1, 5); // Increase the quantity of T-Shirts to 5

// 3. Get the cart contents
$cart_contents = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $product_id => $product_data) {
    echo "<li>" . $product_data['name'] . " - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
  }
  echo "</ul>";
}

// 4. Remove an item from the cart
removeFromCart(2);

// 5. Get the cart contents after removal
$cart_contents = getCartContents();
echo "<h2>Cart Contents After Removal:</h2>";
if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $product_id => $product_data) {
    echo "<li>" . $product_data['name'] . " - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
  }
  echo "</ul>";
}
?>
