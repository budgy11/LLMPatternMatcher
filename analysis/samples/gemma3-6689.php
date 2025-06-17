

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart array if it doesn't exist
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
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
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'] ?? []; // Return cart contents or an empty array if it doesn't exist
}


// Example Usage (Simulating adding items to the cart)

// Add an item to the cart
addToCart(1, 'T-Shirt', 20.00, 2);

// Add another item to the cart
addToCart(2, 'Jeans', 50.00, 1);

// Get the current cart contents
$cart = getCartContents();

// Display the cart contents
echo "<h2>Cart Contents</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_info) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_info['name'] . "<br>";
    echo "<strong>Price:</strong> $" . number_format($product_info['price'], 2) . "<br>";
    echo "<strong>Quantity:</strong> " . $product_info['quantity'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

// Example: Remove an item from the cart
removeFromCart(1);

// Get the cart contents after removing an item
$cart = getCartContents();

// Display the cart contents after removing an item
echo "<h2>Cart Contents After Removal</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $product_info) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $product_info['name'] . "<br>";
        echo "<strong>Price:</strong> $" . number_format($product_info['price'], 2) . "<br>";
        echo "<strong>Quantity:</strong> " . $product_info['quantity'] . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}

// Example: Update the quantity of an item
updateQuantity(2, 3);

//Get the cart contents after updating quantity
$cart = getCartContents();
echo "<h2>Cart Contents After Update</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_info) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_info['name'] . "<br>";
    echo "<strong>Price:</strong> $" . number_format($product_info['price'], 2) . "<br>";
    echo "<strong>Quantity:</strong> " . $product_info['quantity'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}
?>
