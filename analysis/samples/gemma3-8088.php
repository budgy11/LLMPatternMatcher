

<?php

// Database Connection (Replace with your actual database details)
$dbHost = "localhost";
$dbUser = "your_db_user";
$dbPass = "your_db_password";
$dbName = "your_db_name";

// Establish Database Connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


// Function to add a product to the cart
function addToCart($product_id, $quantity, $conn) {
  // This is a basic implementation.  A more robust system would handle 
  // quantity limits, duplicate product checks, and potentially user sessions
  // to track cart items.

  // Sanitize input (important!)
  $product_id = mysqli_real_escape_string($conn, $product_id);
  $quantity = mysqli_real_escape_string($conn, $quantity);


  // Query to check if the product is already in the cart.  If not, we add it.
  $sql = "SELECT * FROM products WHERE id = '$product_id'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // Product exists, update quantity
    $sql = "UPDATE cart SET quantity = quantity + $quantity WHERE product_id = '$product_id'";
    if ($conn->query($sql) === TRUE) {
      // Success
      return true;
    } else {
      // Handle error
      return false;
    }
  } else {
    // Product doesn't exist in the cart, create a new entry
    $sql = "INSERT INTO cart (product_id, quantity) VALUES ('$product_id', $quantity)";
    if ($conn->query($sql) === TRUE) {
      // Success
      return true;
    } else {
      // Handle error
      return false;
    }
  }
}

// Function to get the cart contents
function getCartContents($conn) {
  $sql = "SELECT p.name AS product_name, c.quantity FROM cart c JOIN products p ON c.product_id = p.id";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $cart_data = array();
    while($row = $result->fetch_assoc()) {
      $cart_data[] = $row;
    }
    return $cart_data;
  } else {
    return array(); // Return empty array if cart is empty
  }
}


// Function to remove a product from the cart
function removeFromCart($product_id, $conn) {
    // Sanitize input
    $product_id = mysqli_real_escape_string($conn, $product_id);

    // Delete the row from the cart
    $sql = "DELETE FROM cart WHERE product_id = '$product_id'";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}


// Example usage (Illustrative - you'll need to integrate this into your form)
// -----------------------------------------------------------------------

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Add to cart
  if (isset($_POST["action"]) && $_POST["action"] == "addToCart") {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    if (addToCart($product_id, $quantity, $conn) ) {
      echo "Product added to cart.";
    } else {
      echo "Error adding product to cart.";
    }
  }

  // Remove from cart
  if (isset($_POST["action"]) && $_POST["action"] == "removeFromCart") {
    $product_id = $_POST["product_id"];
    if(removeFromCart($product_id, $conn)) {
      echo "Product removed from cart.";
    } else {
      echo "Error removing product from cart.";
    }
  }

  // Get cart contents
  $cartContents = getCartContents($conn);
  // Display cart contents
  echo "<h2>Your Cart</h2>";
  if (count($cartContents) > 0) {
    echo "<ul>";
    foreach ($cartContents as $item) {
      echo "<li>" . $item['product_name'] . " - Quantity: " . $item['quantity'] . "</li>";
    }
    echo "</ul>";
  } else {
    echo "<p>Your cart is empty.</p>";
  }

}
?>
