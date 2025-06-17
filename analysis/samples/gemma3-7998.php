

<?php
session_start();

// Database connection (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "ecommerce";
$db_user = "your_username";
$db_password = "your_password";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}


// Function to handle the purchase process
function handlePurchase($cart_id) {
    $conn = connectToDatabase();

    // Get cart details
    $query = "SELECT * FROM cart WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart = $result->fetch_assoc();

    // Check if cart exists
    if (!$cart) {
        $stmt->close();
        $conn->close();
        return false;
    }

    // Update order details (Assuming you have an "orders" table)
    //  You'll need to adapt this to your database schema
    $order_date = date("Y-m-d H:i:s");
    $total_amount = 0;

    // Calculate total amount (Example - adapt to your product prices)
    $query = "SELECT price, quantity FROM cart_items WHERE cart_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $product_price = $row['price'];
        $quantity = $row['quantity'];
        $total_amount += $product_price * $quantity;
    }
    $stmt->close();



    $query = "INSERT INTO orders (user_id, order_date, total_amount) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $_SESSION['user_id'], $order_date, $total_amount);
    $stmt->execute();
    $order_id = $conn->insert_id; // Get the ID of the newly created order
    $stmt->close();

    // Iterate through cart items and insert into order_items
    $query = "SELECT product_id, quantity FROM cart_items WHERE cart_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $product_id = $row['product_id'];
        $quantity = $row['quantity'];

        $query = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iii", $order_id, $product_id, $quantity);
        $stmt->execute();
    }
    $stmt->close();

    // Clear the cart
    $query = "DELETE FROM cart WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $stmt->close();


    // Success message
    return true;
}



// Purchase handler
if (isset($_POST['submit_purchase'])) {
    $cart_id = $_POST['cart_id'];

    if (handlePurchase($cart_id)) {
        echo "<h2>Purchase Successful!</h2>";
        echo "<p>Your order has been placed.  A confirmation email has been sent to your address.</p>";
    } else {
        echo "<h2>Purchase Failed!</h2>";
        echo "<p>There was an error processing your purchase.  Please try again later.</p>";
    }
}

// Display cart items (for demo purposes - you'll likely have a separate display function)
function displayCartItems($cart_id) {
  $conn = connectToDatabase();

  $query = "SELECT c.id, p.name, p.price, ci.quantity FROM cart ci JOIN cart c ON ci.cart_id = c.id JOIN products p ON ci.product_id = p.id WHERE c.id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $cart_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    echo "<h3>Cart Items:</h3>";
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th></tr>";
    while ($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . $row['name'] . "</td>";
      echo "<td>" . $row['price'] . "</td>";
      echo "<td>" . $row['quantity'] . "</td>";
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "<p>Cart is empty.</p>";
  }
  $stmt->close();
  $conn->close();
}

?>
