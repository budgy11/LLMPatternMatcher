

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If yes, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If no, add the product to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to get the contents of the cart
function getCartContents() {
  return $_SESSION['cart'];
}

// Example usage (for demonstration purposes)

// Add some items to the cart
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);
addToCart(1, "Laptop", 1200, 1); // Add another laptop

// Display the cart contents
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
$cart_contents = getCartContents();
if (empty($cart_contents)) {
  echo "<li>Cart is empty.</li>";
} else {
  foreach ($cart_contents as $id => $item) {
    echo "<li>" . $item['name'] . " - Price: $" . $item['price'] . " - Quantity: " . $item['quantity'] . " - Total: $" . ($item['price'] * $item['quantity']) . "</li>";
  }
}
echo "</ul>";

// Remove an item (example)
//removeCartItem(2);

// Update quantity (example)
//updateCartQuantity(1, 3);  // Change the quantity of Laptop to 3

// Display updated cart contents (after update example)
//echo "<h2>Updated Cart Contents:</h2>";
//echo "<ul>";
//  $cart_contents = getCartContents();
//   if (empty($cart_contents)) {
//     echo "<li>Cart is empty.</li>";
//   } else {
//     foreach ($cart_contents as $id => $item) {
//       echo "<li>" . $item['name'] . " - Price: $" . $item['price'] . " - Quantity: " . $item['quantity'] . " - Total: $" . ($item['price'] * $item['quantity']) . "</li>";
//     }
//   }
//   echo "</ul>";
?>
