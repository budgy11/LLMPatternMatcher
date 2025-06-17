  </form>

</body>
</html>


<?php
session_start();

// Database connection (replace with your actual credentials)
$dbHost = "localhost";
$dbUser = "your_username";
$dbPass = "your_password";
$dbName = "your_database";

// Database connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cart data (stored in session)
$cart = array();

// Function to add an item to the cart
function addItemToCart($conn, $userId, $product_id, $name, $price, $quantity) {
  global $cart; // Access the global $cart array

  // Check if the item is already in the cart
  $sql = "SELECT id FROM cart WHERE user_id = '$userId' AND product_id = '$product_id'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // Item already exists, update the quantity
    $sql = "UPDATE cart SET quantity = quantity + $quantity WHERE user_id = '$userId' AND product_id = '$product_id'";
    if ($conn->query($sql) === TRUE) {
      //echo "Quantity updated successfully";
    } else {
      echo "Error updating quantity: " . $conn->error;
    }
  } else {
    // Item not in cart, add a new entry
    $sql = "INSERT INTO cart (user_id, product_id, name, price, quantity)
            VALUES ('$userId', '$product_id', '$name', $price, $quantity)";

    if ($conn->query($sql) === TRUE) {
      //echo "New item added to cart successfully";
    } else {
      echo "Error adding item to cart: " . $conn->error;
    }
  }
}

// Function to get cart items
function getCartItems($conn) {
  global $cart; // Access the global $cart array

  $sql = "SELECT c.cart_id, c.user_id, p.product_id, p.name, p.price, c.quantity
          FROM cart c
          JOIN products p ON c.product_id = p.product_id";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $cart_items = array();
    while($row = $result->fetch_assoc()) {
      $cart_items[] = $row;
    }
    return $cart_items;
  } else {
    return array();
  }
}


// Function to remove an item from the cart
function removeItemFromCart($conn, $cart_id) {
  $sql = "DELETE FROM cart WHERE cart_id = '$cart_id'";

  if ($conn->query($sql) === TRUE) {
    //echo "Item removed successfully";
  } else {
    echo "Error removing item from cart: " . $conn->error;
  }
}


// Function to update the quantity of an item in the cart
function updateCartItemQuantity($conn, $cart_id, $quantity) {
  $sql = "UPDATE cart SET quantity = '$quantity' WHERE cart_id = '$cart_id'";

  if ($conn->query($sql) === TRUE) {
    //echo "Quantity updated successfully";
  } else {
    echo "Error updating quantity: " . $conn->error;
  }
}


// --- Purchase Cart Actions (Example - this is just a placeholder) ---

// Add to Cart (Example)
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $name = $_POST['name'];
  $price = $_POST['price'];
  $quantity = $_POST['quantity'];

  addItemToCart($conn, 1, $product_id, $name, $price, $quantity); // Assuming user ID 1
  header("Location: ".$_SERVER['PHP_SELF']."?add_to_cart=success");
  exit();
}

// Display Cart Items
$cart_items = getCartItems($conn);

// Get Cart Total
$total = 0;
foreach ($cart_items as $item) {
  $total += $item['price'] * $item['quantity'];
}

?>
