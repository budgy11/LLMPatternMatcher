

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize an empty cart array
  }

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

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  } else {
    // Product not found in cart, possibly need to add it (handle error appropriately)
    // You could return an error or just do nothing.  For this example, we'll do nothing.
    //echo "Product ID " . $product_id . " not found in cart."; // Optional error message
  }
}

// Function to remove an item from the cart
function removeItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// --- Example Usage (Simulated Product Information - replace with database or API calls) ---

// Add a product to the cart
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);

// Update quantity of product with ID 1
updateQuantity(1, 3);

// Get the cart contents
$cart = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_data) {
    echo "<li>";
    echo "Product: " . $product_data['name'] . "<br>";
    echo "Price: $" . $product_data['price'] . "<br>";
    echo "Quantity: " . $product_data['quantity'] . "<br>";
    echo "Subtotal: $" . $product_data['price'] * $product_data['quantity'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

// Remove a product from the cart
removeItem(2);

// Display updated cart
echo "<h2>Cart Contents (after removal):</h2>";
$cart = getCartContents();
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_data) {
    echo "<li>";
    echo "Product: " . $product_data['name'] . "<br>";
    echo "Price: $" . $product_data['price'] . "<br>";
    echo "Quantity: " . $product_data['quantity'] . "<br>";
    echo "Subtotal: $" . $product_data['price'] * $product_data['quantity'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

?>
