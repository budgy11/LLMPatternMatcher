
<!DOCTYPE html>
<html>
<head>
  <title>Purchase Form</title>
</head>
<body>

  <h1>Make a Purchase</h1>

  <form method="post" action="purchase.php">
    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <button type="submit">Place Order</button>
  </form>

</body>
</html>


<?php
session_start();

// Configuration
$items = []; // Array to store shopping cart items
$cart_file = 'cart.json'; // File to store cart data

// --- Helper Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product to add.
 * @param string $name The name of the product.
 * @param int $quantity The quantity of the product to add.
 * @param float $price The price of the product.
 */
function addToCart($product_id, $name, $quantity, $price) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  $product_exists = false;
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
      $item['quantity'] += $quantity;
      $product_exists = true;
      break;
    }
  }

  // If the product is not in the cart, add it
  if (!$product_exists) {
    $_SESSION['cart'][] = [
      'id' => $product_id,
      'name' => $name,
      'quantity' => $quantity,
      'price' => $price
    ];
  }

  // Save the cart data to the file
  saveCartToFile($_SESSION['cart']);
}


/**
 * Removes an item from the cart by its ID.
 *
 * @param string $product_id The ID of the product to remove.
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
      if ($item['id'] == $product_id) {
        unset($_SESSION['cart'][$key]);
        // Optionally: Re-index the array to avoid gaps
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        break;
      }
    }
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity of the product.
 */
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as &$item) {
      if ($item['id'] == $product_id) {
        $item['quantity'] = $new_quantity;
        break;
      }
    }
  }
}


/**
 * Gets the cart total.
 *
 * @return float The total cost of the cart.
 */
function getCartTotal() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
  }
  return $total;
}


/**
 * Saves the cart data to a JSON file.
 *
 * @param array $cart_data The cart data to save.
 */
function saveCartToFile($cart_data) {
  file_put_contents($cart_file, json_encode($cart_data, JSON_PRETTY_PRINT));
}

/**
 * Loads the cart data from the JSON file.
 */
function loadCartFromFile() {
  global $items; // Access the global array

  if (file_exists($cart_file)) {
    $cart_data = file_get_contents($cart_file);
    if ($cart_data = json_decode($cart_data, true)) {
      $items = $cart_data;
    }
  }
}



// --- Main Script ---

// Load cart data from file
loadCartFromFile();


// --- Cart Actions (Based on Form Submission) ---

if ($_SERVER->is_uploaded_file) { // Check if the form was submitted
  if (isset($_POST['action']) && isset($_POST['product_id'])) {
    $action = $_POST['action'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    switch ($action) {
      case 'add':
        addToCart($product_id, $_POST['name'], $quantity, $_POST['price']);
        break;
      case 'remove':
        removeFromCart($product_id);
        break;
      case 'update':
        updateQuantity($product_id, $quantity);
        break;
    }
  }
}

// --- Cart Display ---

$cart_total = getCartTotal();

echo '<h2>Shopping Cart</h2>';

if (empty($_SESSION['cart'])) {
  echo '<p>Your cart is empty.</p>';
} else {
  echo '<ul>';
  foreach ($_SESSION['cart'] as $item) {
    echo '<li>';
    echo '<span class="product-name">' . $item['name'] . '</span>';
    echo ' - Quantity: ' . $item['quantity'] . ' - Price: $' . $item['price'] . ' - Total: $' . $item['price'] * $item['quantity'] . '</li>';
  }
  echo '</ul>';
  echo '<p><strong>Total: $' . $cart_total . '</strong></p>';
}

?>
