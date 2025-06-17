

<?php
session_start();

// Simulate a shopping cart
$cart = [];

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $cart = [
      $product_id => [
        'name' => $product_name,
        'price' => $price,
        'quantity' => $quantity
      ]
    ];
  } else {
    // Check if the product already exists in the cart
    if (isset($cart[$product_id])) {
      $cart[$product_id]['quantity'] += $quantity;
    } else {
      $cart[$product_id] = [
        'name' => $product_name,
        'price' => $price,
        'quantity' => $quantity
      ];
    }
  }
  //  Save the cart to session
  $_SESSION['cart'] = $cart;
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  unset($cart[$product_id]);
  $_SESSION['cart'] = $cart;
}

// Function to update the quantity of an item in the cart
function updateCartItem($product_id, $new_quantity) {
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $new_quantity;
    }
    $_SESSION['cart'] = $cart;
}

// Function to get the cart items
function getCartItems() {
  return $_SESSION['cart'];
}

// Function to get the total price of items in the cart
function calculateTotalPrice() {
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// --- Example Usage (Simulating User Actions) ---

// Add some items to the cart
addToCart('product1', 'Laptop', 1200, 1);
addToCart('product2', 'Mouse', 25, 2);
addToCart('product1', 'Laptop', 1200, 1); //Adding more of the same item

// Display the cart contents
echo "<h2>Your Shopping Cart:</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

echo "<p>Total Price: $" . calculateTotalPrice() . "</p>";

//Remove an item
removeCartItem('product2');

//Display updated cart
echo "<p>Your Shopping Cart (After Removal):</p>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
}


// Update quantity
updateCartItem('product1', 3);
echo "<p>Your Shopping Cart (After Quantity Update):</p>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
}
?>
