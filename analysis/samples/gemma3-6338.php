
</body>
</html>


<?php
session_start();

// Array to store cart items (key is product ID, value is quantity)
$cart = [];

// Function to add an item to the cart
function addToCart($product_id, $quantity = 1) {
  global $cart;

  if (isset($cart[$product_id])) {
    $cart[$product_id] = $cart[$product_id] + $quantity;
  } else {
    $cart[$product_id] = $quantity;
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id, $quantity = 1) {
  global $cart;

  if (isset($cart[$product_id])) {
    if ($cart[$product_id] > $quantity) {
      $cart[$product_id] -= $quantity;
    } else {
      unset($cart[$product_id]);
    }
  }
}

// Function to get the current cart contents
function getCart() {
  return $cart;
}

// Function to clear the cart (remove all items)
function clearCart() {
    global $cart;
    $cart = [];
}

// ************************
// Example Usage / Demo
// ************************

// 1. Add an item to the cart
addToCart(123);  // Add 1 of product ID 123
addToCart(456, 2); // Add 2 of product ID 456

// 2. View the cart contents
$cartContents = getCart();
echo "<h2>Cart Contents:</h2>";
if (empty($cartContents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartContents as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
  }
  echo "</ul>";
}


// 3. Remove an item from the cart
removeFromCart(123, 1);  // Remove 1 of product ID 123

// 4. View the cart after removal
$cartContents = getCart();
echo "<br><br><h2>Cart Contents After Removal:</h2>";
if (empty($cartContents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cartContents as $product_id => $quantity) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
    }
    echo "</ul>";
}

//5. Clear the cart
//clearCart();
//echo "<br><br><h2>Cart Contents After Clearing:</h2>";
//if (empty($cartContents)) {
//    echo "<p>Your cart is empty.</p>";
//} else {
//    echo "<ul>";
//    foreach ($cartContents as $product_id => $quantity) {
//        echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
//    }
//    echo "</ul>";
//}
?>
