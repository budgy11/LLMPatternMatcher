    </select>
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" value="1" min="1">
    <input type="submit" name="add_to_cart" value="Add to Cart">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Session handling (for user authentication and cart data)
session_start();

// -------------------- Purchase Functionality --------------------

// Function to add an item to the cart (already exists, but included for completeness)
function addToCart($productId, $quantity) {
  if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
  } else {
    $cart = [];
  }

  if (isset($cart[$productId])) {
    $cart[$productId] = $cart[$productId] + $quantity;
  } else {
    $cart[$productId] = $quantity;
  }

  $_SESSION['cart'] = $cart;
}

// Function to process the purchase
function processPurchase() {
  // 1. Validate Cart
  if (empty($_SESSION['cart'])) {
    return false; // Cart is empty, nothing to purchase
  }

  // 2. Get Cart Data
  $cartItems = $_SESSION['cart'];
  $totalAmount = 0;

  // 3.  Simulate Database Interaction (Replace with actual database queries)
  //     This section simulates saving the purchase details to the database.
  //     Replace this with your database interaction code.

  // Example: Simulate adding order information
  $orderDate = date('Y-m-d H:i:s');
  $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null; // Get user ID

  foreach ($cartItems as $productId => $quantity) {
    // Simulate fetching product details from database (replace with your query)
    $product = getProductDetails($productId);  // Assume this function exists and returns product info

    // Calculate price for this item
    $price = $product['price'];
    $itemTotal = $price * $quantity;

    // Add to order details (simulated - replace with database insert)
    //  In a real application, you would insert this into an 'orders' table.

    // For demonstration, let's print the order details
    echo "<p>Product ID: " . $productId . ", Quantity: " . $quantity . ", Price per item: " . $price . ", Total: " . $itemTotal . "</p>";

    $totalAmount += $itemTotal;
  }

  // 4. Clear the Cart
  $_SESSION['cart'] = [];

  // 5.  Success Message
  echo "<p><b>Purchase Successful!</b> Total amount: $" . $totalAmount . "</p>";

  // 6.  Session management (optional - log out after purchase)
  //  You might want to log out the user after a successful purchase.
  //  session_destroy();
  //  session_unset();
}


// -------------------- Helper Functions (Example) --------------------

// Simulate fetching product details from the database
function getProductDetails($productId) {
  // Replace this with your actual database query to get product details
  // This is just a dummy product for demonstration purposes.
  $products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75]
  ];

  if (isset($products[$productId])) {
    return $products[$productId];
  } else {
    return null; // Product not found
  }
}

// --------------------  Page Display (Example) --------------------

// Check if the purchase form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  processPurchase();
}

// Display the cart (for demonstration)
echo "<h2>Cart Items</h2>";
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $productId => $quantity) {
    $product = getProductDetails($productId);
    if ($product) {
      echo "<li>Product: " . $product['name'] . ", Quantity: " . $quantity . "</li>";
    }
  }
  echo "</ul>";
} else {
  echo "<p>Your cart is empty.</p>";
}

?>
