

<?php
session_start();

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Function to connect to the database
function connectToDatabase() {
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to add a product to the cart
function addToCart($product_id, $quantity) {
  $conn = connectToDatabase();

  // Check if the product exists
  $query = "SELECT * FROM products WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    $product_name = $row['name'];
    $product_price = $row['price'];

    // Check if the cart exists for the user
    $cart_id = 'cart_' . session_id(); // Use session ID for cart identification

    // Create the cart entry if it doesn't exist
    $query = "INSERT INTO carts (user_id, product_id, quantity, cart_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isd", null, $product_id, $quantity, $cart_id); //user_id is null since it's for the session
    $stmt->execute();

  } else {
    // Handle product not found (e.g., log an error)
    echo "Product not found in the database.";
  }

  $stmt->close();
  $conn->close();
}

// Function to get the cart contents
function getCartContents() {
  $conn = connectToDatabase();

  // Get the cart ID for the current session
  $cart_id = 'cart_' . session_id();

  $query = "SELECT p.name, p.price, c.quantity FROM carts c JOIN products p ON c.product_id = p.id WHERE c.cart_id = ? ";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $cart_id);
  $stmt->execute();
  $result = $stmt->get_result();

  $cart_items = [];
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $cart_items[] = [
        'id' => $row['product_id'],
        'name' => $row['name'],
        'price' => $row['price'],
        'quantity' => $row['quantity']
      ];
    }
  }

  $stmt->close();
  $conn->close();
  return $cart_items;
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
    $conn = connectToDatabase();

    // Get the cart ID for the current session
    $cart_id = 'cart_' . session_id();

    $query = "UPDATE carts SET quantity = ? WHERE product_id = ? AND cart_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iids", $quantity, $product_id, $cart_id);

    if ($stmt->execute()) {
        // Optionally, you could also implement logic to remove the item if quantity becomes 0.
    } else {
        echo "Error updating cart.";
    }

    $stmt->close();
    $conn->close();
}


// Function to remove an item from the cart
function removeFromCart($product_id, $cart_id) {
    $conn = connectToDatabase();

    $query = "DELETE FROM carts WHERE product_id = ? AND cart_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $product_id, $cart_id);

    if ($stmt->execute()) {
        // Optionally, you can also delete the cart entry if it's the last item
    } else {
        echo "Error removing item from cart.";
    }

    $stmt->close();
    $conn->close();
}


// Example usage (This would be in your HTML form)
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  addToCart($product_id, $quantity);
}

if (isset($_POST['update_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  updateCartQuantity($product_id, $quantity);
}

if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    $cart_id = $_POST['cart_id'];
    removeFromCart($product_id, $cart_id);
}


// Display the cart contents (This would be in your HTML)
$cart_items = getCartContents();

?>
