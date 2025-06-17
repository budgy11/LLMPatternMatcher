

<?php

// Initialize session if not already started
if (!session_id()) {
  session_start();
}

// Array to store cart items
$cart = array();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the item is already in the cart
  if (isset($cart[$product_id])) {
    // If it exists, increase the quantity
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add a new entry
    $cart[$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $quantity;
    }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  unset($cart[$product_id]);
}

// Function to get the cart contents
function getCartContents() {
  return $cart;
}

// Function to calculate the total cart value
function calculateTotal() {
  $total = 0;
  foreach ($cart as $item) {
    $totalItemValue = $item['price'] * $item['quantity'];
    $total += $totalItemValue;
  }
  return $total;
}

//  ------------------- Example Usage and Cart Manipulation -------------------

// 1. Add some items to the cart
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);
addToCart(1, "Laptop", 1200, 2); // Add more of the same item


// 2. Update the quantity of an item
updateQuantity(2, 5); // Change the quantity of the mouse to 5

// 3. Remove an item
removeCartItem(1); // Remove the laptop

// 4. Get the cart contents
$cartContents = getCartContents();
echo "<h2>Cart Contents:</h2>";
echo "<pre>";
print_r($cartContents);
echo "</pre>";

// 5. Calculate and display the total
$total = calculateTotal();
echo "<p>Total Cart Value: $" . number_format($total, 2) . "</p>";


//  ------------------- Session Management (Storing the Cart) -------------------

// Store the cart data in the session
session_start(); // Important:  Start the session if it hasn't been started already
$_SESSION['cart'] = $cart; // Store the cart array in the session

// Output the session data for debugging or demonstration
echo "<p>Cart data stored in session:</p>";
print_r($_SESSION['cart']);
?>
