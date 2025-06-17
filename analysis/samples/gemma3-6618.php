

<?php
session_start();

// Initialize the cart as an empty array if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  } else {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
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
function getCart() {
  return $_SESSION['cart'];
}

// Function to clear the cart
function clearCart() {
  $_SESSION['cart'] = [];
}

// ---------------------  Example Usage (for demonstration)  ---------------------

// Add a product to the cart
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (count($_SESSION['cart']) > 0) {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>" . $product_details['name'] . " - $" . $product_details['price'] . " x " . $product_details['quantity'] . "  <button onclick=\"updateQuantity('" . $product_id . "', parseInt(document.querySelector('#quantity-' . $product_id).value))\">Update Quantity</button></li>";
  }
  echo "</ul>";

  // Add a form to update the quantity of an item
  echo "<form id='update-cart'>";
  foreach($_SESSION['cart'] as $product_id => $product_details){
    echo "<label for='quantity-" . $product_id . "'>Quantity for " . $product_details['name'] . ":</label>
             <input type='number' id='quantity-" . $product_id . "' value='" . $product_details['quantity'] . "' min='1' max='10' name='" . $product_id . "'>";
  }
  echo "<input type='submit' value='Update Cart'>";
  echo "</form>";


  // Add a button to remove an item
  echo "<br><br>";
  echo "<button onclick=\"removeCartItem(1)\">Remove Laptop</button>"; //Example removal

} else {
  echo "<p>Your cart is empty.</p>";
}

// ---------------------  End Example Usage  ---------------------

// You would typically display this cart information within your website's template or page.
//  Further functionality would include adding a 'checkout' button, etc.
?>
