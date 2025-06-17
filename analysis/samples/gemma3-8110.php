

<?php
session_start();

// Configuration (Adjust these for your needs)
$items = []; // Array to store cart items
$cart_key = 'cart_items';

// Function to add an item to the cart
function addItemToCart($productId, $quantity, $productName, $productPrice) {
  global $items, $cart_key;

  if (isset($_SESSION[$cart_key])) {
    $items = $_SESSION[$cart_key];
  } else {
    $items = [];
  }

  // Check if the item already exists in the cart
  $item_exists = false;
  foreach ($items as &$item) { // Use reference (&) to modify the original array
    if ($item['productId'] == $productId) {
      $item['quantity'] += $quantity;
      $item['name'] = $productName;
      $item['price'] = $productPrice;
      $item_exists = true;
      break;
    }
  }

  // If the item doesn't exist, add it to the cart
  if (!$item_exists) {
    $items[] = [
      'productId' => $productId,
      'name' => $productName,
      'price' => $productPrice,
      'quantity' => $quantity
    ];
  }
}

// Function to update the quantity of an item in the cart
function updateCartItemQuantity($productId, $newQuantity) {
  global $items, $cart_key;

  foreach ($items as &$item) {
    if ($item['productId'] == $productId) {
      $item['quantity'] = $newQuantity;
      break;
    }
  }
}

// Function to remove an item from the cart
function removeItemFromCart($productId) {
  global $items, $cart_key;

  // Use array_filter to remove the item
  $items = array_filter($items, function ($item) use ($productId) {
    return $item['productId'] != $productId;
  });
}

// Function to get the cart total
function calculateCartTotal() {
  $total = 0;
  foreach ($items as $item) {
    $total += $item['quantity'] * $item['price'];
  }
  return $total;
}

// Function to get the cart contents
function getCartContents() {
  global $items, $cart_key;
  return $items;
}


// --- Example Usage (simulated product data) ---
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20],
    2 => ['name' => 'Jeans', 'price' => 50],
    3 => ['name' => 'Shoes', 'price' => 80]
];


// --- Handle Add to Cart Request ---
if (isset($_POST['add_to_cart'])) {
  $productId = (int)$_POST['product_id'];
  $quantity = (int)$_POST['quantity'];
  $productName = $products[$productId]['name'];
  $productPrice = $products[$productId]['price'];

  addItemToCart($productId, $quantity, $productName, $productPrice);
  // Redirect to the cart page
  header("Location: cart.php");
  exit();
}

// --- Cart Page (cart.php) ---
?>
