

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>
    <p>Thank you for your order!  (This is a placeholder - implement payment processing here)</p>
    <a href="cart.php">Return to Cart</a>
</body>
</html>


<?php

// Configuration (Adjust these to your actual database details)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_pass = "your_password";

// Function to connect to the database
function connectToDatabase($host, $name, $user, $pass) {
  $conn = new mysqli($host, $user, $pass, $name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to check if an item is already in the cart
function isItemInCart($cart_id, $item_id) {
  $conn = connectToDatabase($db_host, $db_name, $db_user, $db_pass);
  $sql = "SELECT * FROM cart_items WHERE cart_id = ? AND item_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("is", $cart_id, $item_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    return true;
  } else {
    return false;
  }
  $stmt->close();
  $conn->close();
}

// Function to add an item to the cart
function addItemToCart($cart_id, $item_id, $quantity) {
  $conn = connectToDatabase($db_host, $db_name, $db_user, $db_pass);

  $sql = "INSERT INTO cart_items (cart_id, item_id, quantity) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("iss", $cart_id, $item_id, $quantity);

  if ($stmt->execute()) {
    echo "Item added to cart successfully!";
  } else {
    echo "Error adding item to cart.";
  }
  $stmt->close();
  $conn->close();
}

// Function to update the quantity of an item in the cart
function updateQuantityInCart($cart_id, $item_id, $quantity) {
  $conn = connectToDatabase($db_host, $db_name, $db_user, $db_pass);

  $sql = "UPDATE cart_items SET quantity = ? WHERE cart_id = ? AND item_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("iss", $quantity, $cart_id, $item_id);

  if ($stmt->execute()) {
    echo "Quantity updated successfully!";
  } else {
    echo "Error updating quantity.";
  }
  $stmt->close();
  $conn->close();
}

// Function to remove an item from the cart
function removeItemFromCart($cart_id, $item_id) {
  $conn = connectToDatabase($db_host, $db_name, $db_user, $db_pass);

  $sql = "DELETE FROM cart_items WHERE cart_id = ? AND item_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("is", $cart_id, $item_id);

  if ($stmt->execute()) {
    echo "Item removed from cart successfully!";
  } else {
    echo "Error removing item from cart.";
  }
  $stmt->close();
  $conn->close();
}

// --- Example Usage (Simulated Form Handling) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get cart ID from the form (you'll want to store this in a session)
  $cart_id = $_POST["cart_id"];

  // Get item ID and quantity from the form
  $item_id = $_POST["item_id"];
  $quantity = $_POST["quantity"];

  // Add the item to the cart
  addItemToCart($cart_id, $item_id, $quantity);

}
?>
