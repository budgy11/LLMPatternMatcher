</body>
</html>


<?php
session_start();

// Database Connection (Replace with your actual database details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// --- Helper Functions ---

/**
 * Adds an item to the cart
 *
 * @param int $product_id
 * @param int $quantity
 */
function addToCart($product_id, $quantity) {
  if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
  } else {
    $cart = [];
  }

  // Check if the product already exists in the cart
  foreach ($cart as $key => $item) {
    if ($item['product_id'] == $product_id) {
      $cart[$key]['quantity'] += $quantity;
      return;
    }
  }

  // If product not found, add it to the cart
  $cart[$product_id] = [
    'product_id' => $product_id,
    'quantity' => $quantity,
  ];
}

/**
 * Updates the quantity of an item in the cart
 *
 * @param int $product_id
 * @param int $quantity
 */
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];

    // Remove the product if the quantity is 0
    foreach ($cart as $key => $item) {
      if ($item['product_id'] == $product_id && $item['quantity'] <= 0) {
        unset($cart[$key]);
        break; // Exit the loop after removing the item
      }
    }

    // Update the quantity if the product exists
    if (isset($cart[$product_id])) {
      $cart[$product_id]['quantity'] = $quantity;
    }
  }
}


/**
 * Removes an item from the cart
 *
 * @param int $product_id
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
    unset($cart[$product_id]);
  }
}

/**
 * Gets all items in the cart
 *
 * @return array
 */
function getCartItems() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  }
  return [];
}

/**
 * Calculates the total cart value
 *
 * @return float
 */
function calculateCartTotal() {
    $cartItems = getCartItems();
    $total = 0;
    foreach ($cartItems as $item) {
        // Assuming you have a product table with 'price' column
        // Adjust this to your actual product data source
        $product = getProductById($item['product_id']); // Call a function to get product details
        if ($product) {
            $total += $product['price'] * $item['quantity'];
        }
    }
    return $total;
}


/**
 * Retrieves product details by ID
 * @param int $product_id
 * @return array|null
 */
function getProductById($product_id) {
    //  Replace this with your actual database query
    //  This is just an example - adjust to your product table structure
    $query = "SELECT id, name, price FROM products WHERE id = $product_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}


// --- Cart Operations (Based on User Actions) ---

// 1. Add to Cart (e.g., from a product page)
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  addToCart($product_id, $quantity);
}

// 2. Update Cart Quantity (e.g., from a cart page)
if (isset($_POST['update_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  updateCartQuantity($product_id, $quantity);
}

// 3. Remove Item from Cart (e.g., from a cart page)
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  removeFromCart($product_id);
}

// 4. Get Cart Contents (e.g., display in a cart page)
$cartItems = getCartItems();

// 5. Calculate Total
$total = calculateCartTotal();

// --- Display Cart (Example - Cart Page) ---

?>
