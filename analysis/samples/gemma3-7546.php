
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Functionality</title>
</head>
<body>

<h1>Purchase Functionality</h1>

<form method="post" action="">
    <label for="cart_id">Cart ID:</label>
    <input type="text" id="cart_id" name="cart_id" required><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <label for="payment_method">Payment Method:</label>
    <select id="payment_method" name="payment_method">
        <option value="credit_card">Credit Card</option>
        <option value="paypal">PayPal</option>
    </select><br><br>

    <input type="submit" value="Place Order">
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_pass = "your_password";

// Function to connect to the database
function connectToDatabase() {
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
  } catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
  }
}

// Function to add an item to the cart
function addToCart($product_id, $quantity, $pdo) {
  try {
    $stmt = $pdo->prepare("INSERT INTO cart (product_id, quantity) VALUES (?, ?)");
    $stmt->execute([$product_id, $quantity]);
    return true;
  } catch (PDOException $e) {
    // Handle potential errors (e.g., product already in cart)
    return false;
  }
}


// Function to get the cart items
function getCartItems($pdo) {
  try {
    $stmt = $pdo->prepare("SELECT p.name AS product_name, c.quantity FROM cart c JOIN products p ON c.product_id = p.id");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    die("Error retrieving cart items: " . $e->getMessage());
  }
}

// Function to clear the cart
function clearCart($pdo) {
    try {
        $stmt = $pdo->prepare("DELETE FROM cart");
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        die("Error clearing cart: " . $e->getMessage());
    }
}


// Example Usage (handling a purchase - simplified)

// 1. Connect to the database
$pdo = connectToDatabase();

// 2.  Simulate a purchase -  In a real application, this would involve
//     handling payment, order confirmation, etc.  Here, we just "process"
//     the cart.

// Get the cart items
$cart_items = getCartItems($pdo);

// Clear the cart (after "processing")
// clearCart($pdo); // Comment out this line to keep cart items after purchase

// Display the cart items
echo "<h2>Your Cart</h2>";
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $item) {
        echo "<li>" . $item['product_name'] . " - Quantity: " . $item['quantity'] . "</li>";
    }
    echo "</ul>";
}


// 3. Close the database connection (important for security)
$pdo = null;

?>
