
  <hr>

  <a href="index.php">Continue Shopping</a>

</body>
</html>


<?php
session_start();

// Database connection details
$db_host = 'localhost';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';
$db_name = 'your_db_name';

// --- Functions ---

/**
 * Adds an item to the shopping cart
 *
 * @param int $product_id The ID of the product to add
 * @return bool True on success, false on failure
 */
function add_to_cart($product_id) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment quantity
    $_SESSION['cart'][$product_id]['quantity']++;
  } else {
    // Add product to cart
    $_SESSION['cart'][$product_id] = [
      'id' => $product_id,
      'name' => 'Product Name (From Database or Elsewhere)', // Replace with actual product name
      'price' => 10.00, // Replace with actual product price
      'quantity' => 1
    ];
  }
  return true;
}


/**
 * Updates the quantity of an item in the cart
 *
 * @param int $product_id The ID of the product to update
 * @param int $new_quantity The new quantity of the product
 * @return bool True on success, false on failure
 */
function update_cart_quantity($product_id, $new_quantity) {
  if (!isset($_SESSION['cart'][$product_id])) {
    return false; // Product not in cart
  }

  if ($new_quantity <= 0) {
      // Remove item if quantity is zero or negative
      unset($_SESSION['cart'][$product_id]);
      return true;
  }

  $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  return true;
}


/**
 * Removes an item from the cart
 *
 * @param int $product_id The ID of the product to remove
 * @return bool True on success, false on failure
 */
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        return true;
    }
    return false;
}


/**
 * Get the cart contents
 *
 * @return array The cart contents
 */
function get_cart() {
  return $_SESSION['cart'] ?? []; // Return empty array if cart is not set
}



// --- Example Usage / Cart Handling (For the Shopping Cart Page) ---

// Check if the 'add_to_cart' action was triggered (e.g., from a POST request)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
  $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;

  if ($product_id > 0) {
    add_to_cart($product_id);
    // Redirect back to the product page (or the cart page)
    header("Location: product_page.php"); // Replace with your product page URL
    exit();
  } else {
    // Handle invalid product ID
    echo "Invalid product ID.";
  }

}


// Example: Updating cart quantity (Assume this is on a page where you can adjust the quantity)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;
    $new_quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

    if ($product_id > 0 && $new_quantity > 0) {
        update_cart_quantity($product_id, $new_quantity);
        header("Location: cart.php"); // Redirect to cart page
        exit();
    }
}

// Example: Removing an item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'remove_item') {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;
    if($product_id > 0) {
        remove_from_cart($product_id);
        header("Location: cart.php");
        exit();
    }
}



// --- Cart Page (cart.php) ---
?>
