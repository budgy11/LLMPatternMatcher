

<?php

// Database connection details (Replace with your actual credentials)
$dbHost = "localhost";
$dbUsername = "your_username";
$dbPassword = "your_password";
$dbName = "your_database";

// Database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Session handling for cart items
session_start();

// Function to add an item to the cart
function addToCart($productId, $quantity) {
  global $conn;

  // Check if the cart exists in session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    // Product not in cart, add it
    $_SESSION['cart'][$productId] = ['quantity' => $quantity];
  }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($productId, $quantity) {
  global $conn;

  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] = $quantity;
  } else {
    // Product not in cart, add it with quantity 1
    addToCart($productId, 1);
  }
}

// Function to remove an item from the cart
function removeCartItem($productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}

// Function to get the cart items
function getCartItems() {
  return $_SESSION['cart'];
}

// Function to calculate the cart total
function calculateCartTotal() {
  $total = 0;
  $cartItems = getCartItems();
  foreach ($cartItems as $itemId => $item) {
    // Get the product price (Assume you have a products table)
    $productPrice = getProductPrice($itemId); // Implement this function
    $totalForItem = $productPrice * $item['quantity'];
    $total = $total + $totalForItem;
  }
  return $total;
}

// Function to get the price of a product (Replace with your database query)
function getProductPrice($productId) {
    // **IMPORTANT:** This is a placeholder.  Replace with your actual database query.
    //  You'll need to fetch the price from your products table.
    // Example:
    $sql = "SELECT price FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId); // 'i' for integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row['price'];
    } else {
        return 0; // Product not found, return 0 or handle appropriately
    }
    $stmt->close();
}

// ---  Purchase Processing  ---

// If the purchase form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // 1. Validate Input (Crucially Important - Add more robust validation)
  $shippingAddress = $_POST["shipping_address"];
  $paymentMethod = $_POST["payment_method"];

  // 2. Process Payment (Replace with your actual payment gateway integration)
  //   - This is a placeholder - integrate with a payment gateway like Stripe or PayPal
  $orderTotal = calculateCartTotal();
  echo "Order Total: $" . $orderTotal . "<br>";
  echo "Shipping Address: " . $shippingAddress . "<br>";
  echo "Payment Method: " . $paymentMethod . "<br>";

  // 3.  Order Confirmation (You'll need to store this order in a database)
  //   -  Create an order record in your database with the items, quantities,
  //      shipping address, payment details, and order total.

  // 4.  Clear the Cart
  session_start();
  session_destroy();
  echo "Thank you for your order!  Your cart has been emptied.";

}


?>
