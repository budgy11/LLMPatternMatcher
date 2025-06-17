
</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

// Function to connect to the database
function connectDB() {
  $conn = new mysqli($host, $username, $password, $database);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// ---------------------  Cart Functionality ---------------------

// Add item to cart
function addToCart($product_id, $quantity) {
  global $conn;

  // Check if the product exists
  $stmt = $conn->prepare("SELECT id, product_name, price FROM products WHERE id = ?");
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    $product_name = $row['product_name'];
    $product_id = $row['id'];
    $price = $row['price'];

    // Check if the cart already exists for this user
    $cart_id = session_id();

    // Prepare the cart query
    $query = "SELECT id FROM cart WHERE cart_id = ? AND product_id = ?";

    // Check if the product is already in the cart
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $cart_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      // Update quantity if product is already in cart
      $query_update = "UPDATE cart SET quantity = quantity + ? WHERE cart_id = ? AND product_id = ?";
      $stmt_update = $conn->prepare($query_update);
      $stmt_update->bind_param("sss", $quantity, $cart_id, $product_id);
      $stmt_update->execute();
    } else {
      // Add product to cart
      $query_insert = "INSERT INTO cart (cart_id, product_id, quantity) VALUES (?, ?, ?)";
      $stmt_insert = $conn->prepare($query_insert);
      $stmt_insert->bind_param("sss", $cart_id, $product_id, $quantity);
      $stmt_insert->execute();
    }

  } else {
    echo "Product not found.";
  }
}

// Remove item from cart
function removeFromCart($product_id) {
  global $conn;

  // Prepare the query
  $query = "DELETE FROM cart WHERE product_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
}

// Get cart contents
function getCartContents() {
  global $conn;

  $query = "SELECT p.product_name, c.quantity, c.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.cart_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", session_id());
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $cart_items = [];
    while ($row = $result->fetch_assoc()) {
      $cart_items[] = $row;
    }
    return $cart_items;
  } else {
    return [];
  }
}

// Update cart quantity
function updateCartQuantity($product_id, $quantity) {
    global $conn;

    // Prepare the query
    $query = "SELECT id FROM cart WHERE product_id = ? AND cart_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $product_id, session_id());
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update the quantity
        $query_update = "UPDATE cart SET quantity = ? WHERE product_id = ? AND cart_id = ?";
        $stmt_update = $conn->prepare($query_update);
        $stmt_update->bind_param("iss", $quantity, $product_id, session_id());
        $stmt_update->execute();
    } else {
        echo "Product not found in cart.";
    }
}


// ---------------------  Shopping Cart Functions ---------------------

// Initialize cart (add a default item if it doesn't exist)
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Handle adding to cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];

  addToCart($product_id, $quantity);
}

// Handle removing from cart
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  removeFromCart($product_id);
}

// Handle updating cart quantity
if (isset($_POST['update_quantity'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  updateCartQuantity($product_id, $quantity);
}

// Get cart contents for display
$cart_items = getCartContents();


// ---------------------  Checkout (Simple Example) ---------------------

// Total price calculation
$total_price = 0;
if (!empty($cart_items)) {
    foreach ($cart_items as $item) {
        $total_price += $item['quantity'] * $item['price'];
    }
}

// ---------------------  Display Cart Contents ---------------------

echo "<h2>Shopping Cart</h2>";

if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $item) {
        echo "<li>" . $item['product_name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
    echo "<p>Total Price: $" . $total_price . "</p>";
}

?>
