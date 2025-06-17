

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product already exists in the cart.  If so, increment quantity.
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add a new entry
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to get the cart contents
function getCartContents() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  } else {
    return array(); // Return an empty array if the cart is empty
  }
}


// Example Usage (Simulated Product Data)
$products = array(
  1 => array('name' => 'Laptop', 'price' => 1200),
  2 => array('name' => 'Mouse', 'price' => 25),
  3 => array('name' => 'Keyboard', 'price' => 75)
);


// 1. Adding items to the cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];

  if (isset($products[$product_id])) {
    addToCart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
  }
}


// 2.  Removing an item
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  removeCartItem($product_id);
}

// 3.  Updating Quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    updateCartQuantity($product_id, $new_quantity);
}

// 4. Displaying the cart contents
$cartContents = getCartContents();

echo "<h2>Shopping Cart</h2>";

if (empty($cartContents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartContents as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}


?>
