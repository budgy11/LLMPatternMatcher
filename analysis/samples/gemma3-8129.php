

<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Initialize the cart
$_SESSION['cart'] = array();

// Function to add items to the cart
function addToCart($product_id, $product_name, $price, $quantity) {
  $_SESSION['cart'][] = array(
    'id' => $product_id,
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $quantity;
            return true;
        }
    }
    return false;
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  foreach ($_SESSION['cart'] as $key => $item) {
    if ($item['id'] == $product_id) {
      unset($_SESSION['cart'][$key]);
      return true;
    }
  }
  return false;
}


// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Function to calculate the total cart value
function calculateTotal() {
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total = $total + ($item['price'] * $item['quantity']);
  }
  return $total;
}


// ---  Example Usage & Handling (Illustrative - Adapt to your product/database system) ---

// 1. Adding an item to the cart (e.g., from a form submission)
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $product_name = $_POST['product_name'];
  $price = $_POST['price'];
  $quantity = $_POST['quantity'];

  addToCart($product_id, $product_name, $price, $quantity);
  echo "<p>Item added to cart!</p>";
}

// 2. Updating quantity (e.g., from a form submission)
if (isset($_POST['update_cart'])) {
  $product_id = $_POST['product_id'];
  $new_quantity = $_POST['quantity'];
  updateCartQuantity($product_id, $new_quantity);
  echo "<p>Quantity updated!</p>";
}

// 3. Removing an item from the cart
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  removeCartItem($product_id);
  echo "<p>Item removed from cart!</p>";
}

// 4. Displaying the cart contents
$cart_contents = getCartContents();

if (!empty($cart_contents)) {
  echo "<h2>Your Shopping Cart</h2>";
  echo "<ul>";
  foreach ($cart_contents as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . calculateTotal() . "</strong></p>";
} else {
  echo "<p>Your cart is empty.</p>";
}

?>
