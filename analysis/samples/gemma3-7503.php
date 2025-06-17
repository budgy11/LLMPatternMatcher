
<br>
<a href="products.php">View Products</a>  <!-- Link to your products page -->

</body>
</html>


<?php

// This script handles the purchase functionality

// --- Database Configuration (Replace with your actual database details) ---
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_pass = "your_password";
// --- End Database Configuration ---

//  --- Helper Functions ---
function connectToDatabase() {
  global $db_host, $db_name, $db_user, $db_pass;
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
  } catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
  }
}

function addItemToCart($productId, $userId, $pdo) {
  // Simple example:  Adds quantity of 1 to cart.  Adjust based on your cart implementation
  $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, 1)";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':user_id', $userId);
  $stmt->bindParam(':product_id', $productId);
  return $stmt->execute();
}

function getCartItems($userId, $pdo) {
  $sql = "SELECT p.product_name, c.quantity FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = :user_id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':user_id', $userId);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateCartItemQuantity($productId, $userId, $quantity, $pdo) {
    // More robust approach for updating quantity
    $sql = "UPDATE cart SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':product_id', $productId);
    $stmt->bindParam(':quantity', $quantity);
    return $stmt->execute();
}


// --- Purchase Handling ---

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['submit_purchase'])) {
    //  Get user ID (assuming you have a user authentication system)
    $userId = $_SESSION["user_id"]; // Replace with your user ID retrieval mechanism

    //  Cart Items:  Get cart items
    $cartItems = getCartItems($userId, $pdo);

    //  Check if the cart is empty
    if (empty($cartItems)) {
      echo "<p>Your cart is empty. Please add items before proceeding to checkout.</p>";
      return;
    }


    //  Process the purchase (This is a simplified example)
    echo "<h2>Purchase Summary</h2>";
    echo "<ul>";
    foreach ($cartItems as $item) {
      echo "<li>Product: " . $item["product_name"] . ", Quantity: " . $item["quantity"] . "</li>";
    }
    echo "</ul>";

    //  Simulate order processing -  In a real application, this would create a new order record
    echo "<p>Order placed successfully! (Simulated)</p>";

    // Clear the cart after the purchase
    clearCart($userId, $pdo);
  }
}

// --- Helper Functions for Cart Management ---

function clearCart($userId, $pdo) {
  $sql = "DELETE FROM cart WHERE user_id = :user_id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':user_id', $userId);
  return $stmt->execute();
}


?>
