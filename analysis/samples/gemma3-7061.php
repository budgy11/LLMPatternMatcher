
</body>
</html>


<?php
session_start();

// Database connection details (Replace with your actual credentials)
$dbHost = "localhost";
$dbUser = "your_db_user";
$dbPass = "your_db_password";
$dbName = "your_db_name";

// Initialize an empty cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to connect to the database
function connectToDatabase() {
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to add an item to the cart
function addToCart($conn, $product_id, $quantity) {
  $product_id = intval($product_id); // Ensure product ID is an integer
  $quantity = intval($quantity);   // Ensure quantity is an integer

  if ($quantity <= 0) {
    return false; // Invalid quantity
  }

  // Retrieve product information from the database
  $productQuery = "SELECT id, name, price FROM products WHERE id = $product_id";
  $productResult = $conn->query($productQuery);

  if (!$productResult->num_rows > 0) {
    return false; // Product not found
  }

  $product = $productResult->fetch_assoc();
  $product_name = $product['name'];
  $product_price = $product['price'];

  // Add the item to the cart
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = ['quantity' => $quantity, 'name' => $product_name, 'price' => $product_price];
  } else {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  }

  return true;
}

// Function to remove an item from the cart
function removeFromCart($conn, $product_id) {
    $product_id = intval($product_id); // Ensure product ID is an integer

    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($conn, $product_id, $quantity) {
    $product_id = intval($product_id); // Ensure product ID is an integer
    $quantity = intval($quantity);   // Ensure quantity is an integer

    if (!isset($_SESSION['cart'][$product_id])) {
        return false; // Product not in cart
    }

    if ($quantity <= 0) {
        return false; // Invalid quantity
    }

    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    return true;
}

// Function to calculate the total cart value
function calculateTotal($conn) {
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// ---  Handle Cart Actions  ---

// Add to Cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];

  if (addToCart($conn, $product_id, $quantity)) {
    echo "<p>Item added to cart.</p>";
  } else {
    echo "<p>Error adding item to cart.</p>";
  }
}

// Remove from Cart
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  removeFromCart($conn, $product_id);
  echo "<p>Item removed from cart.</p>";
}

// Update Quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    if (updateQuantity($conn, $product_id, $quantity)) {
        echo "<p>Quantity updated successfully.</p>";
    } else {
        echo "<p>Failed to update quantity.</p>";
    }
}


// --- Display Cart Contents ---
$total = calculateTotal($conn);

echo "<h2>Your Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . $total . "</strong></p>";
}

?>
