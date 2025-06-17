

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $quantity) {
  // Check if the cart already exists in the session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart as an empty array
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, update the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'price' => 0 // Replace with the actual product price
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
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'] ?? []; // Returns the cart or an empty array if it doesn't exist
}


// --- Example Usage (Simulating user interactions) ---

// 1. Add an item to the cart
addToCart(1, 2); // Add 2 units of product with ID 1

// 2. Add another item
addToCart(2, 1);

// 3. Update the quantity of product 1 to 5
updateQuantity(1, 5);

// 4. Remove product 2 from the cart
removeCartItem(2);

// 5. Get the cart contents
$cart = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . ", Price: " . $item['price'] . "</li>";
  }
  echo "</ul>";
}

//Example of adding a product with a price
function addToCartWithPrice($product_id, $quantity, $price) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'price' => $price
    ];
  }
}

addToCartWithPrice(3, 1, 19.99);

$cart = getCartContents();
echo "<h2>Cart Contents with Price:</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . ", Price: " . $item['price'] . "</li>";
  }
  echo "</ul>";
}
?>
