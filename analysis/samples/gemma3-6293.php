

<?php

// Initialize a session if it doesn't exist
session_start();

// --- Cart Functionality ---

// Function to add an item to the cart
function addToCart($product_id, $quantity) {
  // Check if the cart already exists in session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = array(
      'quantity' => $quantity,
      'price' => $product_price //Assuming you have a $product_price variable
    );
  }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    } else {
      // If product not found, potentially add it or handle error
      // Here we just ignore the update
      // echo "Product not found in cart: " . $product_id . "<br>";
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
  return $_SESSION['cart'];
}

// Function to calculate the total price of the cart
function calculateTotalPrice() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['quantity'] * $item['price']; // Assuming $item['price'] is the price
    }
  }
  return $total;
}

// --- Example Usage (Demonstration) ---

// Let's simulate some products and prices (replace with your actual product data)
$product_prices = [
  1 => 10.00,  // Product ID 1
  2 => 20.00,  // Product ID 2
  3 => 5.00,   // Product ID 3
];

// ---  Simulate user adding items to the cart ---

// User adds 2 of Product 1
addToCart(1, 2);

// User adds 1 of Product 2
addToCart(2, 1);

// User updates the quantity of Product 1 to 5
updateCartQuantity(1, 5);

// User removes Product 2 from the cart
removeCartItem(2);

// --- Display the Cart Contents ---

// Get the cart contents
$cart = getCartContents();

echo "<h2>Your Cart:</h2>";

if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . "<br>";
    echo "  Quantity: " . $item['quantity'] . "<br>";
    echo "  Price: $" . $product_prices[$product_id] . "<br>";
    echo "  Total Price for this item: $" . ($item['quantity'] * $product_prices[$product_id]) . "<br>";
    echo "</li>";
  }
  echo "</ul>";

  // Calculate and display the total price
  $totalPrice = calculateTotalPrice();
  echo "<p><strong>Total Cart Price: $" . $totalPrice . "</strong></p>";
}
?>
