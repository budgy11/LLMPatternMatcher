

<?php
session_start();

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity  The quantity to add. Defaults to 1.
 * @return void
 */
function add_to_cart(int $product_id, int $quantity = 1) {
  // Check if the cart already exists
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function remove_from_cart(int $product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function update_cart_quantity(int $product_id, int $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

/**
 * Gets all items in the cart.
 *
 * @return array An array containing the items in the cart.
 */
function get_cart() {
  return $_SESSION['cart'] ?? []; // Use null coalesce operator for empty array
}

/**
 * Gets the total number of items in the cart.
 *
 * @return int
 */
function get_cart_item_count() {
    return count(get_cart());
}


// --- Example Usage (Simulated Product Data - Replace with your actual database) ---

//Simulate a product list
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20],
    2 => ['name' => 'Jeans', 'price' => 50],
    3 => ['name' => 'Hat', 'price' => 15],
];



// --- Handling Add to Cart Request (Simulated) ---

if (isset($_POST['add_to_cart'])) {
  $product_id = (int)$_POST['product_id'];
  $quantity = (int)$_POST['quantity'] ?? 1; // Default to 1 if quantity not provided

  if (isset($products[$product_id])) {
    add_to_cart($product_id, $quantity);
  } else {
    // Handle invalid product ID
    echo "Product ID $product_id not found.";
  }
}

// --- Handling Remove from Cart Request (Simulated) ---

if (isset($_POST['remove_from_cart'])) {
  $product_id = (int)$_POST['product_id'];
  remove_from_cart($product_id);
}


// --- Displaying the Cart ---

echo "<h2>Your Shopping Cart</h2>";

$cart_items = get_cart();

if (count($cart_items) > 0) {
  echo "<ul>";
  foreach ($cart_items as $product_id => $quantity) {
    $product_name = $products[$product_id]['name'];
    $product_price = $products[$product_id]['price'];
    echo "<li>Product: $product_name - Quantity: $quantity - Price: $product_price</li>";
  }
  echo "</ul>";
} else {
  echo "<p>Your cart is empty.</p>";
}

echo "<p>Total Items in Cart: " . get_cart_item_count() . "</p>";



?>
