

<?php
session_start();

// --- Cart Initialization ---
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// --- Helper Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product being added.
 * @param int $quantity The quantity of the product being added.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = 0;
  }
  $_SESSION['cart'][$product_id] += $quantity;
}

/**
 * Removes a product from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Returns the cart contents.
 *
 * @return array The cart contents as an array.
 */
function getCartContents() {
  return $_SESSION['cart'];
}

/**
 *  Calculates the total number of items in the cart.
 *
 * @return int The total number of items
 */
function getCartTotal(){
  $total = 0;
  if(isset($_SESSION['cart'])){
    foreach($_SESSION['cart'] as $quantity => $count){
      $total = $total + $count;
    }
  }
  return $total;
}

// --- Example Usage (Simulated Product Details) ---
// This is just for demonstration; in a real application, you'd
// fetch this data from a database or other source.

$products = [
  1 => ['name' => 'T-Shirt', 'price' => 20],
  2 => ['name' => 'Jeans', 'price' => 50],
  3 => ['name' => 'Hat', 'price' => 15]
];


// ---  Handling Cart Actions (Simulated) ---

// 1. Adding to Cart
if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
  $product_id = $_POST['product_id'];
  $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;
  addToCart($product_id, $quantity);
  echo "<p>Item added to cart!</p>";
}

// 2. Removing from Cart
if (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
  $product_id = $_POST['product_id'];
  removeFromCart($product_id);
  echo "<p>Item removed from cart!</p>";
}


// --- Displaying the Cart ---
echo "<h2>Your Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    echo "<li>";
    echo "Product ID: " . $product_id . "<br>";
    echo "Product Name: " . $products[$product_id]['name'] . "<br>";
    echo "Quantity: " . $quantity . "<br>";
    echo "Price: $" . $products[$product_id]['price'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

echo "<p>Total Items in Cart: " . getCartTotal() . "</p>";

?>
