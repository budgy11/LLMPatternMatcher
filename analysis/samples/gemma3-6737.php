

<?php
session_start();

// Initialize cart data if not already set
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add new item to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update quantity of an item in the cart
function update_cart_quantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get cart contents
function get_cart_contents() {
  return $_SESSION['cart'];
}


// ---  Shopping Cart Operations ---

// 1. Add to Cart (Handle POST request)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; // Default quantity is 1
    add_to_cart($product_id, $product_name, $price, $quantity);
  } elseif (isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
    $product_id = $_POST['product_id'];
    $new_quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
    update_cart_quantity($product_id, $new_quantity);
  } elseif (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
    $product_id = $_POST['product_id'];
    remove_from_cart($product_id);
  }
}



// --- Display the Cart ---

// Get cart contents
$cart_contents = get_cart_contents();

// Calculate total price
$total_price = 0;
if (count($cart_contents) > 0) {
  foreach ($cart_contents as $item_id => $item_data) {
    $total_price += $item_data['price'] * $item_data['quantity'];
  }
}

?>
