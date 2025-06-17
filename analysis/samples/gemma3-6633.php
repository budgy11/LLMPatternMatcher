

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  $_SESSION['cart'][$product_id] = array(
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  unset($_SESSION['cart'][$product_id]);
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}


// Example Usage:
// -----------------------------------

// 1. Add an item to the cart
addToCart(1, "Awesome T-Shirt", 25.00, 2); // Product ID 1, Name, Price, Quantity
addToCart(2, "Cool Mug", 10.00, 1); // Product ID 2

// 2. Update the quantity of an item
updateCartQuantity(1, 3); // Increase the quantity of product 1 to 3

// 3. Get the cart contents
$cart_contents = getCartContents();
echo "<h2>Cart Contents:</h2>";
echo "<pre>";
print_r($cart_contents);
echo "</pre>";

// 4. Remove an item from the cart
removeCartItem(2);

// Get cart contents again to see the change
$cart_contents = getCartContents();
echo "<h2>Cart Contents After Removal:</h2>";
echo "<pre>";
print_r($cart_contents);
echo "</pre>";


// --- Example HTML to display the cart contents (in a real application, this would be dynamically generated) ---
?>
