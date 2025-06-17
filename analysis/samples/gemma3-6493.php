

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add new product to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
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

// Function to get the cart items
function getCartItems() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  } else {
    return [];
  }
}

//  ------------------  Example Usage (Simulated Products ) -------------------

// Simulating products (replace with your database or other data source)
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75]
];


// ---------------------  Handle Add to Cart Request  -----------------------

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $products[$product_id]['name'];
    $price = $products[$product_id]['price'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; // Default quantity is 1

    addToCart($product_id, $product_name, $price, $quantity);

    // Redirect to the cart page
    header("Location: cart.php"); // Or your specific cart page
    exit();
}

// ---------------------  Handle Remove Item Request  -----------------------

if (isset($_GET['remove_from_cart'])) {
  $product_id = $_GET['remove_from_cart'];
  removeCartItem($product_id);
  header("Location: cart.php");
  exit();
}

// ---------------------  Handle Update Quantity Request  -----------------------

if (isset($_GET['update_quantity'])) {
    $product_id = $_GET['update_quantity'];
    $new_quantity = $_GET['quantity'];
    updateCartQuantity($product_id, $new_quantity);
    header("Location: cart.php");
    exit();
}

?>
