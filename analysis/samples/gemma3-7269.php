

<?php
session_start();

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_pass = "your_db_password";
$db_name = "your_db_name";

// Ensure the database connection works
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to check if a product is in the cart
function isInCart($product_id) {
  global $conn;
  if (isset($_SESSION['cart'][$product_id]['quantity'])) {
    return true;
  } else {
    return false;
  }
}

// Function to update cart quantity
function updateCartQuantity($product_id, $quantity) {
  global $conn;

  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    $_SESSION['cart'][$product_id]['quantity'] = max(0, $_SESSION['cart'][$product_id]['quantity']); // Ensure quantity doesn't go below 0
  } else {
    // If product not in cart, add it with the specified quantity
    $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
  }
}

// Function to remove a product from the cart
function removeProductFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get cart total
function getCartTotal($cart) {
    $total = 0;
    foreach ($cart as $product_id => $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Handle adding to cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];

  // Validate quantity (optional, but recommended)
  if (empty($quantity) || $quantity <= 0) {
    echo "<p style='color:red;'>Invalid quantity.</p>";
    exit;
  }

  updateCartQuantity($product_id, $quantity);
  echo "<p>Product added to cart.</p>";
}

// Handle removing from cart
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  removeProductFromCart($product_id);
  echo "<p>Product removed from cart.</p>";
}

// Handle updating quantity
if (isset($_POST['update_quantity'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];

  // Validate quantity
  if (empty($quantity) || $quantity <= 0) {
    echo "<p style='color:red;'>Invalid quantity.</p>";
    exit;
  }
  updateCartQuantity($product_id, $quantity);
}


// Display the cart contents
$cart_items = [];
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $item) {
        // Fetch product details from your database
        $product_query = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($product_query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($product) {
            $cart_items[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $item['quantity']
            ];
        }
    }
}

$cart_total = getCartTotal($cart_items);


?>
