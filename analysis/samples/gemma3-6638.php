

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $quantity) {
  // Check if the cart exists in session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Add the product to the cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
  }
}

// Function to update the quantity of a product in the cart
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  } else {
    // If the product isn't in the cart, it's an error.  Handle this differently if desired.
    echo "Error: Product ID $product_id not found in the cart.";
    // Or, you could add the product with the given quantity.
  }
}

// Function to remove a product from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart contents
function getCart() {
  return $_SESSION['cart'];
}

// Function to clear the cart
function clearCart() {
  unset($_SESSION['cart']);
}

// --- Example Usage (Simulating User Actions) ---

// 1. Add a product to the cart (User adds item 1 in quantity 2)
addToCart(1, 2);

// 2. Add another product to the cart (User adds item 2 in quantity 1)
addToCart(2, 1);

// 3. Update the quantity of item 1 to 5
updateCartQuantity(1, 5);

// 4. View the cart contents
$cartContents = getCart();
echo "<h2>Cart Contents:</h2>";
if (empty($cartContents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<pre>";
  print_r($cartContents);
  echo "</pre>";
}


// 5. Remove item 2 from the cart
removeCartItem(2);

// 6. View the cart contents again
$cartContents = getCart();
echo "<h2>Cart Contents After Removal:</h2>";
if (empty($cartContents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<pre>";
  print_r($cartContents);
  echo "</pre>";
}

// 7. Clear the cart
clearCart();

// 8. View the cart contents after clearing
$cartContents = getCart();
echo "<h2>Cart Contents After Clearing:</h2>";
if (empty($cartContents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<pre>";
  print_r($cartContents);
  echo "</pre>";
}

?>
