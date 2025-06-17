

<?php

session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increase quantity if product exists
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update quantity of an existing item
function updateCartQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
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
  return $_SESSION['cart'] ?? []; // Return an empty array if cart is empty
}


// Example Usage (Simulated Shopping Cart)
// -------------------------------------

// Add a product to the cart
addToCart(1, "Laptop", 1200, 1); // Product ID 1: Laptop, Quantity: 1
addToCart(2, "Mouse", 25, 2);   // Product ID 2: Mouse, Quantity: 2
addToCart(1, "Laptop", 1200, 2); // Add another laptop

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
$cart = getCartContents();

if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_data) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_data['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_data['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_data['quantity'] . "<br>";
    echo "<strong>Total for this item:</strong> $" . ($product_data['price'] * $product_data['quantity']) . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

// Example of updating a quantity
updateCartQuantity(2, 5);

// Display the updated cart
echo "<br><h2>Updated Cart</h2>";
$cart = getCartContents();

if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_data) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_data['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_data['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_data['quantity'] . "<br>";
    echo "<strong>Total for this item:</strong> $" . ($product_data['price'] * $product_data['quantity']) . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

// Example of removing an item
removeCartItem(2);

// Example of getting cart contents again
echo "<br><h2>Final Cart</h2>";
$cart = getCartContents();

if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_data) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_data['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_data['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_data['quantity'] . "<br>";
    echo "<strong>Total for this item:</strong> $" . ($product_data['price'] * $product_data['quantity']) . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

?>
