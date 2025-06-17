
    <form method="post">
      <div class="form-group">
        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" required>
      </div>
      <div class="form-group">
        <label for="product_price">Product Price:</label>
        <input type="number" id="product_price" name="product_price" required>
      </div>
      <div class="form-group">
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>
      </div>
      <button type="submit">Add to Cart</button>
    </form>
  </div>
</body>
</html>


<?php
session_start();

// Database connection (replace with your actual credentials)
$dbHost = "localhost";
$dbUser = "your_db_user";
$dbPass = "your_db_password";
$dbName = "your_db_name";

// Create a database connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

//  Configuration
$items_per_page = 8; // Number of items to display per page
$total_items = 0;
$page = 1;

//  Shopping Cart Functions

/**
 * Add an item to the shopping cart.
 *
 * @param int $product_id The ID of the product to add.
 * @return bool True if added successfully, false otherwise.
 */
function add_to_cart($product_id) {
  global $conn;

  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity']++;
  } else {
    // If not, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'id' => $product_id,
      'quantity' => 1,
      'name' =>  // Get product name from database -  See functions below for this
          "", // placeholder for now - add database query here
      'price' =>  // Get product price from database -  See functions below for this
          0.00,   // placeholder for now - add database query here
    ];
  }
  return true;
}

/**
 * Update the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity for the product.
 * @return bool True if updated successfully, false otherwise.
 */
function update_cart_quantity($product_id, $new_quantity) {
  global $conn;

  if (isset($_SESSION['cart'][$product_id])) {
    if ($new_quantity > 0) {
      $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
      return true;
    } else {
      //  Remove item from cart if quantity is 0
      unset($_SESSION['cart'][$product_id]);
      return true;
    }
  }
  return false;
}

/**
 * Remove an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return bool True if removed successfully, false otherwise.
 */
function remove_from_cart($product_id) {
  global $conn;

  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
    return true;
  }
  return false;
}

/**
 * Get the total cart value.
 *
 * @return float The total cart value.
 */
function get_cart_total() {
  global $conn;
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['quantity'] * $item['price'];
    }
  }
  return $total;
}

/**
 *  Get cart contents.
 *
 * @return array The contents of the cart.
 */
function get_cart_contents() {
    return $_SESSION['cart'];
}

/**
 * Clear the cart
 *
 * @return bool True if cleared successfully, false otherwise.
 */
function clear_cart() {
    global $conn;
    unset($_SESSION['cart']);
    return true;
}


// Example Product Data - Replace with your database queries
$products = [
    [
        'id' => 1,
        'name' => 'Laptop',
        'price' => 1200.00
    ],
    [
        'id' => 2,
        'name' => 'Mouse',
        'price' => 25.00
    ],
    [
        'id' => 3,
        'name' => 'Keyboard',
        'price' => 75.00
    ]
];


// Shopping Cart Handling

// Add to cart functionality
if (isset($_POST['add_to_cart']) && isset($_POST['product_id'])) {
  $product_id = intval($_POST['product_id']);
  add_to_cart($product_id);
}

// Update cart quantity
if (isset($_POST['update_quantity']) && isset($_POST['product_id']) && isset($_POST['quantity'])) {
  $product_id = intval($_POST['product_id']);
  $new_quantity = intval($_POST['quantity']);
  update_cart_quantity($product_id, $new_quantity);
}

// Remove from cart
if (isset($_POST['remove_from_cart']) && isset($_POST['product_id'])) {
  $product_id = intval($_POST['product_id']);
  remove_from_cart($product_id);
}

// Clear Cart
if (isset($_POST['clear_cart'])) {
    clear_cart();
}

// Get Cart Contents
$cart_contents = get_cart_contents();

// Calculate total
$total = get_cart_total();

// Get page number
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = intval($_GET['page']);
    //  Pagination logic goes here -  Example:
    $offset = ($page - 1) * $items_per_page;
    $limited_cart = array_slice($cart_contents, $items_per_page, $offset);
} else {
    $page = 1;
    $limited_cart = $cart_contents; // All cart contents if no page is specified.
}


// Display the Cart
?>
