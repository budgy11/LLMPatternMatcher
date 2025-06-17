

<?php

session_start();

// --- Example Cart Data (Replace with database or other storage) ---
$cart = [];

// Function to add items to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
  } else {
    $cart = [];
  }

  $item = [
    'id' => $product_id,
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  ];

  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    $cart[$product_id] = $item;
  }

  // Keep only items with quantity > 0 (optional, but recommended)
  $cart = array_filter($cart, function($item) {
    return $item['quantity'] > 0;
  });
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
    unset($cart[$product_id]);
  }
}

// Function to update quantity of an item in cart
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];

    if (isset($cart[$product_id])) {
      $cart[$product_id]['quantity'] = $quantity;
    }
  }
}

// Function to get the cart contents
function getCartContents() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  } else {
    return [];
  }
}

// Function to clear the cart (session)
function clearCart() {
  unset($_SESSION['cart']);
}


// --- Example Usage (Simulating User Interactions) ---

// Add some items to the cart
addToCart(1, "T-Shirt", 20, 2);
addToCart(2, "Jeans", 50, 1);
addToCart(1, "T-Shirt", 20, 3); // Add more of the same item

// Display the cart contents
$cartContents = getCartContents();
echo "<h2>Your Cart:</h2>";
if (empty($cartContents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartContents as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}


// Simulate removing an item
//removeFromCart(2);
//echo "<h2>Your Cart after removing jeans:</h2>";
//echo $cartContents;

//Update quantity
//updateQuantity(1, 5);
//echo "<h2>Your Cart after updating T-shirt quantity:</h2>";
//echo $cartContents;

// Clear the cart (e.g., after checkout)
//clearCart();
//echo "<h2>Your Cart after clearing:</h2>";
//echo $cartContents;

?>
