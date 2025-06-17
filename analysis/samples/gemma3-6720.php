

<?php
session_start();

// Array to store items in the cart
$cart = array();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  global $cart;

  if (empty($cart)) {
    $cart[$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  } else {
    // Check if the product already exists in the cart
    if (isset($cart[$product_id])) {
      $cart[$product_id]['quantity'] += $quantity;
    } else {
      $cart[$product_id] = array(
        'name' => $product_name,
        'price' => $price,
        'quantity' => $quantity
      );
    }
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    global $cart;

    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $new_quantity) {
  global $cart;

  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] = $new_quantity;
  }
}

// Example usage (Simulating product data)
$products = array(
  1 => array('name' => 'T-Shirt', 'price' => 20),
  2 => array('name' => 'Jeans', 'price' => 50),
  3 => array('name' => 'Hat', 'price' => 15)
);


//  Handling Add to Cart requests (e.g., from a form)
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $product_name = $products[$product_id]['name'];
  $price = $products[$product_id]['price'];
  $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; // Default to 1 if not provided

  addToCart($product_id, $product_name, $price, $quantity);

  // Redirect to a cart page (or update the current page)
  header("Location: cart.php"); // Replace 'cart.php' with the appropriate URL
  exit();
}


// Handling Remove Cart Item requests
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    removeCartItem($product_id);

    header("Location: cart.php");
    exit();
}

// Handling Update Quantity requests
if (isset($_POST['update_quantity'])) {
  $product_id = $_POST['product_id'];
  $new_quantity = $_POST['quantity'];
  updateCartQuantity($product_id, $new_quantity);

  header("Location: cart.php");
  exit();
}

// Displaying the cart contents (only in a cart page, e.g., cart.php)
?>
