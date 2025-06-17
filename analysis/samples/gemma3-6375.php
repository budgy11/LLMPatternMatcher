

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Functions for Cart Operations ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function add_to_cart($product_id, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  } else {
    $_SESSION['cart'][$product_id] += $quantity;
  }
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity of the product.
 * @return void
 */
function update_cart_quantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $new_quantity;
    }
}

/**
 * Gets the cart contents.
 *
 * @return array The cart contents.
 */
function get_cart_contents() {
  return $_SESSION['cart'];
}

// --- Example Usage (Simulated Product Data - Replace with your actual database) ---

$products = [
  1 => ['name' => 'T-Shirt', 'price' => 20],
  2 => ['name' => 'Jeans', 'price' => 50],
  3 => ['name' => 'Hat', 'price' => 15],
];


// --- Handling User Actions (Example) ---

if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];  // Get quantity from POST
  add_to_cart($product_id, $quantity);
}

if (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
  $product_id = $_POST['product_id'];
  remove_from_cart($product_id);
}

if (isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    update_cart_quantity($product_id, $new_quantity);
}


// --- Displaying the Cart Contents ---

echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $product = $products[$product_id];
    echo "<li>Product: " . $product['name'] . ", Quantity: " . $quantity . ", Price: $" . $product['price'] . "</li>";
  }
  echo "</ul>";
}

// --- Example Form for Adding to Cart ---
echo "<h2>Add to Cart</h2>";
echo "<form method='post'>
        <label for='product_id'>Product ID:</label>
        <select name='product_id' id='product_id'>
            <option value='1'>T-Shirt</option>
            <option value='2'>Jeans</option>
            <option value='3'>Hat</option>
        </select><br><br>

        <label for='quantity'>Quantity:</label>
        <input type='number' id='quantity' name='quantity' value='1' min='1'><br><br>

        <button type='submit' name='action' value='add_to_cart'>Add to Cart</button>
      </form>";

// Example form for updating quantity
echo "<h2>Update Quantity</h2>";
echo "<form method='post'>
        <label for='product_id'>Product ID:</label>
        <select name='product_id' id='product_id'>
            <option value='1'>T-Shirt</option>
            <option value='2'>Jeans</option>
            <option value='3'>Hat</option>
        </select><br><br>

        <label for='new_quantity'>New Quantity:</label>
        <input type='number' id='new_quantity' name='quantity' value='1' min='1'><br><br>

        <button type='submit' name='action' value='update_quantity'>Update Quantity</button>
      </form>";

?>
