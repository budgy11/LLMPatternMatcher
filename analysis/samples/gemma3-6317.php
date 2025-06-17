

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment quantity if product exists
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add new product to the cart
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
function updateCartItem($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}


// Example Usage (Illustrative - this would be handled by a form submission)
// Assume a product ID of 1, product name "Laptop", price $1000, quantity of 1
// You would get this information from a form submission or other input.

//Example: Adding a product to the cart
addToCart(1, 'Laptop', 1000);

// Example: Removing an item from the cart (assuming product ID 1)
//removeCartItem(1);

// Example: Updating the quantity of an item in the cart (assuming product ID 1 and desired quantity of 2)
//updateCartItem(1, 2);


// Display the cart contents
$cartContents = getCartContents();
echo "<h2>Shopping Cart</h2>";
if (empty($cartContents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartContents as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

?>
