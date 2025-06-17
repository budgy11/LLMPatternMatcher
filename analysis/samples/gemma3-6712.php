

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $quantity) {
  // Check if the cart already exists in session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If product exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If product doesn't exist, add it to the cart with quantity 1
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
  }
}

// Function to remove a product from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateCartItem($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to get the cart items
function getCartItems() {
  return $_SESSION['cart'];
}

// Function to clear the cart
function clearCart() {
    unset($_SESSION['cart']);
}

// ------------------  Example Usage (Demonstration) ------------------

// Add some products to the cart
addToCart(1, 2); // Product ID 1, Quantity 2
addToCart(2, 1); // Product ID 2, Quantity 1
addToCart(1, 3); // Product ID 1, Quantity 3

// Display the cart contents
echo "<h2>Cart Contents:</h2>";
$cartItems = getCartItems();

if (empty($cartItems)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartItems as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}


// Example of updating a quantity
updateCartItem(1, 5); //Update quantity of Product 1 to 5

echo "<br>";
echo "<h2>Cart Contents After Update:</h2>";
$cartItems = getCartItems();

if (empty($cartItems)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartItems as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}


// Example of removing an item from the cart
removeCartItem(2);

echo "<br>";
echo "<h2>Cart Contents After Removal:</h2>";
$cartItems = getCartItems();

if (empty($cartItems)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartItems as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}


// Example of clearing the cart
clearCart();

echo "<br>";
echo "<h2>Cart Contents After Clearing:</h2>";
$cartItems = getCartItems();

if (empty($cartItems)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartItems as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}
?>
