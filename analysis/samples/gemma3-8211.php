

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $quantity) {
  global $session;

  if (isset($session['cart'][$product_id])) {
    $session['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $session['cart'][$product_id] = array('quantity' => $quantity);
  }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
    if (isset($session['cart'][$product_id])) {
        $session['cart'][$product_id]['quantity'] = $quantity;
    }
}


// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($session['cart'][$product_id])) {
    unset($session['cart'][$product_id]);
  }
}

// Function to calculate the total cart value
function calculate_total() {
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['quantity'] * $item['price']; // Assuming 'price' is in each item
  }
  return $total;
}


// Example usage (This part would be handled by the add_to_cart.php or view_cart.php)
if (isset($_GET['action']) && $_GET['action'] == 'update') {
  $product_id = $_GET['product_id'];
  $quantity = $_GET['quantity'];
  update_cart_quantity($product_id, $quantity);
}

if (isset($_GET['action']) && $_GET['action'] == 'remove') {
  $product_id = $_GET['product_id'];
  remove_from_cart($product_id);
}

?>
