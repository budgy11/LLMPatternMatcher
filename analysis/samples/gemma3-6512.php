
</body>
</html>


<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// --- Functions for Cart Management ---

// Add a product to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product not in cart, add it
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Remove a product from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Update the quantity of a product in the cart
function updateCartItem($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}


// Get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}


// --- Example Usage (Simulated Products - Replace with your product data) ---

// Simulate a product data array (replace with your actual database or product data source)
$products = array(
  1 => array('name' => 'Laptop', 'price' => 1200),
  2 => array('name' => 'Mouse', 'price' => 25),
  3 => array('name' => 'Keyboard', 'price' => 75)
);


// ---  Example Cart Interactions ---

// Add a Laptop to the cart
addToCart(1, $products[1]['name'], $products[1]['price']);

// Add a Mouse to the cart
addToCart(2, $products[2]['name'], $products[2]['price'], 2); // Add 2 mice

// Get the current cart contents
$cart = getCartContents();
echo "<h2>Cart Contents:</h2>";
echo "<pre>";
print_r($cart);
echo "</pre>";


// Remove the Mouse
removeCartItem(2);

// Get the updated cart contents
$cart = getCartContents();
echo "<h2>Cart Contents After Removal:</h2>";
echo "<pre>";
print_r($cart);
echo "</pre>";

// Update the quantity of the Laptop to 3
updateCartItem(1, 3);
$cart = getCartContents();
echo "<h2>Cart Contents After Quantity Update:</h2>";
echo "<pre>";
print_r($cart);
echo "</pre>";
?>
