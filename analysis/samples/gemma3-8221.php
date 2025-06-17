

<?php
session_start();

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Check if the cart is empty, initialize it if necessary
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// --- Helper Functions ---

/**
 * Add an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        $_SESSION['cart'][$product_id] += $quantity;
    }
}

/**
 * Remove an item from the cart.
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
 * Get the cart contents.
 *
 * @return array The cart contents as an array.
 */
function getCart() {
  return $_SESSION['cart'];
}

/**
 * Calculate the cart total.
 *
 * @return float The total cart value.
 */
function calculateTotal() {
  $total = 0;
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    // You'll need to fetch product prices from your database here
    // For demonstration purposes, let's assume product prices are hardcoded
    $product_price = getProductPrice($product_id); // Implement this function
    $total_for_product = $product_price * $quantity;
    $total = $total + $total_for_product;
  }
  return $total;
}

/**
 *  Placeholder function to get product price from DB.  Replace with your actual DB query.
 * @param int $product_id
 * @return float The product price
 */
function getProductPrice($product_id) {
    //  Replace this with your database query to fetch the price based on product_id
    // Example using a dummy hardcoded price:
    switch ($product_id) {
        case 1: return 10.00;
        case 2: return 25.50;
        case 3: return 5.00;
        default: return 0.00; // Or throw an error if the product doesn't exist
    }
}


// --- Handling Add to Cart Request ---

if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($product_id, $quantity);
    // Optionally, redirect back to the product page or cart page.
    // header("Location: product.php");
}

// --- Handling Remove from Cart Request ---

if (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
    $product_id = $_POST['product_id'];
    removeFromCart($product_id);
    // Optionally, redirect back to the product page or cart page.
    // header("Location: product.php");
}


// --- Displaying the Cart ---

// Get the cart contents
$cart_contents = getCart();

// Calculate the cart total
$total = calculateTotal();

// Start the HTML output
?>
