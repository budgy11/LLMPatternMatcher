

<?php
session_start();

// Check if the cart is empty or not
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Example function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity) {
  // Check if the item already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Item exists, increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Item doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Example function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Example function to update the quantity of an item
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Example function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Example function to clear the cart
function clearCart() {
  unset($_SESSION['cart']);
}


// ----------------------------------------------------------------------
//  Here's where you would typically handle user interactions like:
//  - Form submission to add items to the cart.
//  - Displaying the cart contents.
//  - Allowing users to update quantities or remove items.
// ----------------------------------------------------------------------

// --- Example:  Simulating a form submission to add an item ---
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $product_name = $_POST['product_name'];
  $price = $_POST['price'];
  $quantity = $_POST['quantity'];

  addToCart($product_id, $product_name, $price, $quantity);
  // Display a success message
  echo "<p>Item added to cart!</p>";
}

// --- Example: Removing an item ---
if (isset($_GET['remove_item'])) {
  $product_id = $_GET['remove_item'];
  removeFromCart($product_id);
  // Display a message
  echo "<p>Item removed from cart.</p>";
}

// --- Example: Updating quantity ---
if (isset($_GET['update_quantity'])) {
  $product_id = $_GET['update_quantity'];
  $new_quantity = $_POST['quantity'];
  updateQuantity($product_id, $new_quantity);
  echo "<p>Quantity updated!</p>";
}

// --- Example: Getting and displaying the cart contents ---
$cart_contents = getCartContents();

echo "<h2>Your Shopping Cart</h2>";
if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}

// --- Example: Clearing the cart (e.g., on checkout) ---
if (isset($_GET['clear_cart'])) {
  clearCart();
  echo "<p>Your cart has been cleared.</p>";
}


?>
