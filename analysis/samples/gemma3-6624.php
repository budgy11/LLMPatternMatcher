

<?php
session_start();

// Initialize the cart (empty array) if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the item is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Item exists, increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Item doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function removeItemFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Example Usage:

// Add an item to the cart
addToCart(1, 'Laptop', 1200, 1);
addToCart(2, 'Mouse', 25, 2);

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
    echo "<strong>Price:</strong> $" . number_format($product_details['price'], 2) . "<br>";
    echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
    echo "<strong>Total:</strong> $" . number_format(($product_details['price'] * $product_details['quantity']), 2) . "<br>";
    echo "<form action='' method='post'>";
    echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
    echo "<input type='submit' value='Remove from Cart'>";
    echo "</form>";
    echo "</li>";
  }
  echo "</ul>";
}

// Example of removing an item
// removeItemFromCart(2);

// Example of updating quantity
// updateQuantity(1, 3);

// Display the updated cart contents (after update)
// echo "<h2>Updated Shopping Cart</h2>";
// if (empty($_SESSION['cart'])) {
//   echo "<p>Your cart is empty.</p>";
// } else {
//   echo "<ul>";
//   foreach ($_SESSION['cart'] as $product_id => $product_details) {
//     echo "<li>";
//     echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
//     echo "<strong>Price:</strong> $" . number_format($product_details['price'], 2) . "<br>";
//     echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
//     echo "<strong>Total:</strong> $" . number_format(($product_details['price'] * $product_details['quantity']), 2) . "<br>";
//     echo "</li>";
//   }
//   echo "</ul>";
// }
?>
