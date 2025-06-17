
<a href="checkout.php">Checkout</a>  <!-- Replace checkout.php with your actual checkout page -->

</body>
</html>


<?php
session_start();

// Database connection (replace with your actual database credentials)
$db_host = "localhost";
$db_user = "your_user";
$db_password = "your_password";
$db_name = "your_database";

// Create database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to add an item to the cart
function addToCart($conn, $product_id, $quantity) {
  if (empty($_SESSION['cart'])) {
    // Cart is empty, create a new cart array
    $_SESSION['cart'] = array();
  }

  // Check if product already exists in cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = array(
      'quantity' => $quantity,
      'product_id' => $product_id  // For easy updates later
    );
  }
}

// Function to update quantity of an item in the cart
function updateQuantity($conn, $product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}


// Function to remove an item from the cart
function removeFromCart($conn, $product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}


// Function to get the cart contents
function getCartContents($conn) {
  $cart_contents = array();
  foreach ($_SESSION['cart'] as $product_id => $item) {
    $query = "SELECT product_name, price FROM products WHERE product_id = " . $product_id;
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $cart_contents[] = array(
        'product_id' => $product_id,
        'product_name' => $row['product_name'],
        'price' => $row['price'],
        'quantity' => $item['quantity']
      );
    }
  }
  return $cart_contents;
}

// --- Cart Actions ---

// Add to Cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];

  addToCart($conn, $product_id, $quantity);
  // Redirect to cart page
  header("Location: cart.php");
  exit();
}

// Update Quantity
if (isset($_POST['update_quantity'])) {
  $product_id = $_POST['product_id'];
  $new_quantity = $_POST['quantity'];
  updateQuantity($conn, $product_id, $new_quantity);
  header("Location: cart.php");
  exit();
}

// Remove from Cart
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  removeFromCart($conn, $product_id);
  header("Location: cart.php");
  exit();
}

// Get Cart Contents
$cart_contents = getCartContents($conn);

// --- Display Cart Page (cart.php) ---

?>
