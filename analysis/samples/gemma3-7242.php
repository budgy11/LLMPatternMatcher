    <label for="userId">User ID:</label>
    <input type="number" id="userId" name="userId" required><br><br>

    <label for="productId">Product ID:</label>
    <input type="number" id="productId" name="productId" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <button type="submit">Make Purchase</button>
</form>

</body>
</html>


<?php
session_start();

// Database connection details (Replace with your actual credentials)
$dbHost = "localhost";
$dbUser = "your_db_user";
$dbPass = "your_db_password";
$dbName = "your_db_name";

// --- Shopping Cart Functions ---

/**
 * Add an item to the shopping cart
 *
 * @param int $productId The ID of the product to add
 * @param int $quantity  The quantity to add
 */
function addToCart($productId, $quantity) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$productId] = array(
      'quantity' => $quantity,
      'price'    => getProductPrice($productId) // Ensure price is accurate
    );
  }
}

/**
 * Get the price of a product
 *
 * @param int $productId The ID of the product
 * @return float|null The price of the product, or null if not found
 */
function getProductPrice($productId) {
  //  Simulate fetching price from database
  // Replace this with your actual database query
  $products = array(
    1 => array('name' => 'Laptop', 'price' => 1200.00),
    2 => array('name' => 'Mouse', 'price' => 25.00),
    3 => array('name' => 'Keyboard', 'price' => 75.00)
  );

  if (isset($products[$productId])) {
    return $products[$productId]['price'];
  } else {
    return null; // Product not found
  }
}

/**
 * Update the quantity of an item in the cart
 *
 * @param int $productId The ID of the product to update
 * @param int $quantity  The new quantity
 */
function updateCartQuantity($productId, $quantity) {
  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] = $quantity;
  }
}

/**
 * Remove an item from the cart
 *
 * @param int $productId The ID of the product to remove
 */
function removeCartItem($productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}

/**
 * Get the cart contents
 *
 * @return array The shopping cart contents
 */
function getCartContents() {
  return $_SESSION['cart'];
}


/**
 * Calculate the total cart value
 *
 * @return float The total value of the cart
 */
function calculateTotal() {
  $total = 0;
  $cart = getCartContents();

  foreach ($cart as $item) {
    $total += $item['quantity'] * $item['price'];
  }
  return $total;
}


// --- Handle Add to Cart Request ---
if (isset($_POST['add_to_cart'])) {
  $productId = (int)$_POST['product_id']; // Ensure product_id is an integer
  $quantity = (int)$_POST['quantity'];

  addToCart($productId, $quantity);
  //  You might want to redirect the user after adding to the cart
  header("Location: cart.php");  // Redirect to the cart page
  exit();
}

// --- Handle Update Cart Request ---
if (isset($_POST['update_cart'])) {
  $productId = (int)$_POST['product_id'];
  $quantity = (int)$_POST['quantity'];

  updateCartQuantity($productId, $quantity);
  header("Location: cart.php");
  exit();
}

// --- Handle Remove Cart Item Request ---
if (isset($_POST['remove_from_cart'])) {
    $productId = (int)$_POST['product_id'];
    removeCartItem($productId);
    header("Location: cart.php");
    exit();
}

// --- Display Cart Contents ---
$cart = getCartContents();
$total = calculateTotal();

?>
