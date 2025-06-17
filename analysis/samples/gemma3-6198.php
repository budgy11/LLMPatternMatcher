

<?php

// Start a session to store the cart data
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it is, increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it's not, add it to the cart as a new item
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $newQuantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $newQuantity;
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Example Usage (demonstration)

// Add some items to the cart
addToCart(1, 'Laptop', 1200, 1);
addToCart(2, 'Mouse', 25, 2);
addToCart(1, 'Laptop', 1200, 1);  //Add a second Laptop

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
echo "<ul>";
$cart_items = getCartContents();
if (empty($cart_items)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart_items as $product_id => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
}
echo "</ul>";


// Example: Remove an item
// removeFromCart(2);

// Example: Update quantity
// updateQuantity(1, 3);

//Display updated cart
echo "<h2>Your Shopping Cart (Updated)</h2>";
$cart_items = getCartContents();
if (empty($cart_items)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart_items as $product_id => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
}
echo "</ul>";

?>
