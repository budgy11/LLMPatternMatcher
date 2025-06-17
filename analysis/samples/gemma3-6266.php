

<?php
session_start();

// Function to add an item to the cart
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

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
    
    // Optional:  If you want to remove items with 0 quantity
    // if (empty($_SESSION['cart'])) {
    //   $_SESSION['cart'] = array();
    // }
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to get the cart items
function getCartItems() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  }
  return array(); // Return an empty array if the cart is empty
}

// Example Usage (Simulating a product listing and a user action)
// In a real application, you'd get this data from a database or other source.

$products = array(
  1 => array('name' => 'Shirt', 'price' => 20),
  2 => array('name' => 'Pants', 'price' => 30),
  3 => array('name' => 'Shoes', 'price' => 50)
);

// 1. Add items to the cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];

  // Validate the product ID
  if (array_key_exists($product_id, $products)) {
    addToCart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
  }
}

// 2. Remove an item
if (isset($_GET['remove_item'])) {
  $product_id = $_GET['remove_item'];
  removeFromCart($product_id);
}


// 3. Update Quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    updateQuantity($product_id, $new_quantity);
}

// 4. Display the cart items
$cart_items = getCartItems();

?>
