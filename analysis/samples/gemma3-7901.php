

<?php

// Database Connection (Replace with your actual database credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Session Handling (To store user information and cart)
session_start();

// Cart data (Initialize if not already set)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ------------------- Product Information (Dummy for example) -------------------
// This is just a sample product; replace with your product data source
$products = [
    1 => [
        'id' => 1,
        'name' => 'T-Shirt',
        'price' => 20.00,
        'description' => 'A comfortable cotton T-shirt.',
        'image' => 'tshirt.jpg' // Add image path here
    ],
    2 => [
        'id' => 2,
        'name' => 'Jeans',
        'price' => 50.00,
        'description' => 'Classic blue denim jeans.',
        'image' => 'jeans.jpg' // Add image path here
    ]
];

// ------------------- Purchase Functionality -------------------

// Function to add an item to the cart
function addToCart($productId, $quantity) {
  global $conn, $products;

  // Validate product ID
  if (!isset($products[$productId])) {
    return false; // Product not found
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$productId] = [
      'id' => $productId,
      'name' => $products[$productId]['name'],
      'price' => $products[$productId]['price'],
      'quantity' => $quantity
    ];
  }
  return true;
}

// Function to remove an item from the cart
function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
    return true;
}

// Function to update the quantity of an item in the cart
function updateQuantity($productId, $quantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    }
    return true;
}

// Function to calculate the total cart value
function calculateTotal() {
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// Function to process the purchase (This is a simplified example)
function processPurchase() {
  //  1. Validate Cart
  if (empty($_SESSION['cart'])) {
      return false; // Cart is empty
  }

  // 2. Calculate Total
  $total = calculateTotal();

  // 3. Clear Cart (After successful purchase)
  $_SESSION['cart'] = [];

  // 4.  You would typically:
  //     -  Validate payment information (e.g., using a payment gateway)
  //     -  Update order details in your database
  //     -  Send confirmation emails

  // Simulate a successful purchase
  return true;
}

// -------------------  Handle Actions (GET/POST) -------------------

// Handle adding to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($productId, $quantity);
}

// Handle removing from cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_from_cart'])) {
    $productId = $_POST['product_id'];
    removeFromCart($productId);
}

// Handle updating quantity
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_quantity'])) {
    $productId = $_POST['product_id'];
    $newQuantity = $_POST['quantity'];
    updateQuantity($productId, $newQuantity);
}


// Handle the purchase action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['purchase'])) {
    if (processPurchase()) {
        echo "<p>Order placed successfully!  Your cart is now empty.</p>";
    } else {
        echo "<p>Order placement failed. Please try again.</p>";
    }
}

// ------------------- Display Cart -------------------

// Display cart contents
echo "<h2>Shopping Cart</h2>";
if (!empty($_SESSION['cart'])) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<li>";
        echo "<h3>" . $item['name'] . "</h3>";
        echo "Price: $" . number_format($item['price'], 2) . "<br>";
        echo "Quantity: " . $item['quantity'] . "<br>";
        echo "Subtotal: $" . number_format($item['price'] * $item['quantity'], 2) . "<br>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='product_id' value='" . $item['id'] . "'>";
        echo "<input type='submit' name='remove_from_cart' value='Remove from Cart'>";
        echo "</form>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='product_id' value='" . $item['id'] . "'>";
        echo "<input type='number' name='quantity' value='" . $item['quantity'] . "' min='1'>";
        echo "<input type='submit' name='update_quantity' value='Update Quantity'>";
        echo "</form>";
        echo "<br>";
    }
    echo "</ul>";
    echo "<p>Total Cart Value: $" . number_format(calculateTotal(), 2) . "</p>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='purchase' value='1'>";
    echo "<input type='submit' name='purchase' value='Place Order'>";
    echo "</form>";
} else {
    echo "<p>Your cart is empty.</p>";
}
?>
