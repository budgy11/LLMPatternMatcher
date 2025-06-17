

<?php
session_start();

// ------------------------------------------------------------------
//  Cart Implementation (Simplified - for demonstration)
// ------------------------------------------------------------------

// Function to add an item to the cart
function addToCart($product_id, $quantity) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to get the cart items
function getCartItems() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  }
  return array();
}

// ------------------------------------------------------------------
//  Example Usage (Simulated Products - Replace with your actual data)
// ------------------------------------------------------------------

// Dummy product data (replace with your database query)
$products = array(
  1 => array('name' => 'T-Shirt', 'price' => 20),
  2 => array('name' => 'Jeans', 'price' => 50),
  3 => array('name' => 'Hat', 'price' => 15)
);


// ------------------------------------------------------------------
//  Session Handling - Example Actions
// ------------------------------------------------------------------

// 1. Add an item to the cart (e.g., user adds a T-Shirt)
if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  addToCart($product_id, $quantity);
  echo "<p>Item added to cart.</p>";
}

// 2. Remove an item from the cart (e.g., user removes Jeans)
if (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
  $product_id = $_POST['product_id'];
  removeFromCart($product_id);
  echo "<p>Item removed from cart.</p>";
}

// 3. Update quantity (e.g., user changes the quantity of a T-Shirt)
if (isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
  $product_id = $_POST['product_id'];
  $new_quantity = $_POST['quantity'];
  updateQuantity($product_id, $new_quantity);
  echo "<p>Quantity updated in cart.</p>";
}


// ------------------------------------------------------------------
//  Displaying the Cart
// ------------------------------------------------------------------

// Get cart items
$cart_items = getCartItems();

echo "<h2>Your Cart</h2>";

if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $product_id => $item) {
    $product_name = $products[$product_id]['name'];
    $product_price = $products[$product_id]['price'];
    $quantity = $item['quantity'];
    $total_price = $product_price * $quantity;

    echo "<li>" . $product_name . " - $" . $product_price . " x " . $quantity . " = $" . $total_price . "</li>";
  }
  echo "</ul>";

  // Calculate the total cart value
  $total_cart_value = 0;
  foreach ($cart_items as $product_id => $item) {
    $total_price = $products[$product_id]['price'] * $item['quantity'];
    $total_cart_value += $total_price;
  }

  echo "<p><strong>Total Cart Value: $" . $total_cart_value . "</strong></p>";
}

?>
