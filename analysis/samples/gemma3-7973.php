
</body>
</html>


<?php

// Database Connection (Replace with your actual database credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Establish the database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ---------------------  Shopping Cart Functionality  ---------------------

// Function to add an item to the cart
function addToCart($conn, $userId, $productId, $quantity) {
  //  (This is a basic example, in a real application you'd also handle
  //  validation, error checking, and potentially database transactions.)

  // Check if the item already exists in the cart for this user
  $sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $userId, $productId);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // Item already in cart, increment quantity
    $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("iii", $quantity, $userId, $productId);
    $stmt->execute();
  } else {
    // Item not in cart, add it
    $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $userId, $productId, $quantity);
    $stmt->execute();
  }

  return true; // Indicate success
}

// Function to get the contents of the cart
function getCartContents($conn, $userId) {
  $sql = "SELECT * FROM cart WHERE user_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();

  $cart_items = [];
  while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
  }

  return $cart_items;
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($conn, $userId, $productId, $quantity) {
  // Check if the item exists in the cart for this user
  $sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $userId, $productId);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // Item exists, update quantity
    $sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $quantity, $userId, $productId);
    $stmt->execute();
  } else {
    // Item doesn't exist, return false.  You might want to add an error log here.
    return false;
  }

  return true;
}


// Function to remove an item from the cart
function removeFromCart($conn, $userId, $productId) {
    $sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
    return true;
}


// ---------------------  Purchase Functionality  ---------------------

//  This is a simplified purchase function.  A real application would involve:
//  - Payment processing (e.g., Stripe, PayPal)
//  - Order fulfillment (updating inventory, generating shipping labels)
//  - Order confirmation emails

// Function to process a purchase (simplified)
function processPurchase($conn, $userId, $productId, $quantity) {
    // 1. Get the item's price (assuming a 'products' table exists)
    $sql = "SELECT price FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        // Product not found
        return false;
    }

    $item_price = $product['price'];

    // 2. Calculate the total price
    $total_price = $item_price * $quantity;

    // 3.  Remove the items from the cart
    if (!removeFromCart($conn, $userId, $productId)) {
        // Handle error - could not remove item from cart.  Critical error!
        return false;
    }

    // 4.  (In a real application, you'd integrate with a payment processor here)
    //     For this example, we'll just simulate a payment.

    // 5.  Update order table (example - create an 'orders' table)
    //    This is a placeholder.
    $sql = "INSERT INTO orders (user_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $userId, $productId, $quantity, $total_price);
    $stmt->execute();



    return true;  // Indicate successful purchase
}


// ---------------------  Example Usage (Illustrative)  ---------------------

//  This is just to demonstrate how the functions might be called.

// 1. Add an item to the cart
if (isset($_POST['add_to_cart'])) {
    $userId = 1; // Example user ID
    $productId = 101; // Example product ID
    $quantity = $_POST['quantity'];

    if (addToCart($conn, $userId, $productId, $quantity)) {
        echo "Item added to cart.<br>";
    } else {
        echo "Failed to add item to cart.<br>";
    }
}

// 2.  Get Cart Contents
$cart_items = getCartContents($conn, 1);
echo "<h2>Cart Contents:</h2>";
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $item) {
        echo "<li>Product ID: " . $item['product_id'] . ", Quantity: " . $item['quantity'] . "</li>";
    }
    echo "</ul>";
}

// 3.  Process a Purchase (example)
if (isset($_POST['purchase'])) {
    $userId = 1;
    $productId = 101;
    $quantity = $_POST['quantity'];
    if (processPurchase($conn, $userId, $productId, $quantity)) {
        echo "Purchase successful!  (Simulated)<br>";
    } else {
        echo "Purchase failed.<br>";
    }
}


// Close the database connection
$conn->close();
?>
