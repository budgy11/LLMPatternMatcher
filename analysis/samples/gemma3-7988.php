
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>
  <h1>Shopping Cart</h1>

  <form method="post" action="">
    <label for="cart_items">Cart Items:</label>
    <textarea id="cart_items" name="cart_items" rows="10" cols="50">
      1,2
    </textarea>
    <br>
    <button type="submit">Place Order</button>
  </form>
</body>
</html>


<?php

// Database Connection (Replace with your actual database credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_pass = "your_password";

// Connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to add an item to the cart
function addToCart($conn, $product_id, $quantity) {
  // Check if the product ID exists (optional, but good practice)
  // You can add a query here to verify product existence and prevent invalid IDs.
  // For simplicity, we'll assume the product exists.

  //  Prepare the SQL statement
  $sql = "INSERT INTO cart (product_id, quantity) VALUES (?, ?)";

  // Prepare statement
  $stmt = $conn->prepare($sql);

  if ($stmt) {
      // Bind parameters
      $stmt->bind_param("ii", $product_id, $quantity);

      // Execute the statement
      if ($stmt->execute()) {
          return true;
      } else {
          error_log("Query execution failed: " . $conn->error);
          return false;
      }
  } else {
      error_log("Query preparation failed");
      return false;
  }
  // Close statement
  $stmt->close();
}

// Function to get the cart contents
function getCartContents($conn) {
  $sql = "SELECT p.product_name, c.quantity FROM cart c JOIN products p ON c.product_id = p.product_id";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $cart_data = array();
    while($row = $result->fetch_assoc()) {
      $cart_data[] = $row;
    }
    return $cart_data;
  } else {
    return [];
  }
}



// Example Usage (Simulating a Purchase)
// Assuming this is called during a purchase process

// 1. Add items to the cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];

  if (addToCart($conn, $product_id, $quantity)) {
    echo "<p>Item added to cart!</p>";
  } else {
    echo "<p>Error adding item to cart.</p>";
  }
}


// 2.  Example of getting and displaying the cart contents
$cart_items = getCartContents($conn);

echo "<h2>Your Shopping Cart</h2>";
if (count($cart_items) > 0) {
    echo "<ul>";
    foreach ($cart_items as $item) {
        echo "<li>" . $item['product_name'] . " - Quantity: " . $item['quantity'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Your cart is empty.</p>";
}



// Close the database connection
$conn->close();

?>
