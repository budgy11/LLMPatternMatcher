

<?php
session_start();

// Function to add item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  $_SESSION['cart'][$product_id] = array(
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to remove item from the cart
function removeItemFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart contents
function getCartContents() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  } else {
    return array();
  }
}

// --- Example Usage (simulated product data - Replace with your actual product data) ---

$products = array(
  1 => array('name' => 'Laptop', 'price' => 1200),
  2 => array('name' => 'Mouse', 'price' => 25),
  3 => array('name' => 'Keyboard', 'price' => 75)
);

// ---  Handling Cart Actions (Example - Replace with your actual form handling) ---

// 1. Add to Cart (Simulated)
if (isset($_POST['add_to_cart'])) {
  $product_id = (int)$_POST['product_id']; // Ensure product_id is an integer
  $quantity = (int)$_POST['quantity']; // Ensure quantity is an integer
  addToCart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
  echo "Product '$products[$product_id]['name']' added to cart.<br>";
}

// 2. Update Quantity (Simulated)
if (isset($_POST['update_quantity'])) {
  $product_id = (int)$_POST['product_id'];
  $new_quantity = (int)$_POST['quantity'];
  updateQuantity($product_id, $new_quantity);
  echo "Quantity of Product '$products[$product_id]['name']' updated to $new_quantity.<br>";
}

// 3. Remove Item from Cart (Simulated)
if (isset($_POST['remove_from_cart'])) {
    $product_id = (int)$_POST['product_id'];
    removeItemFromCart($product_id);
    echo "Product '$products[$product_id]['name']' removed from cart.<br>";
}

// --- Displaying the Cart Contents (for demonstration) ---
$cart_contents = getCartContents();

if (!empty($cart_contents)) {
  echo "<h2>Cart Contents:</h2>";
  echo "<ul>";
  foreach ($cart_contents as $product_id => $item) {
    echo "<li>Product: " . $item['name'] . ", Price: $" . $item['price'] . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
} else {
  echo "<p>Your cart is empty.</p>";
}

?>
