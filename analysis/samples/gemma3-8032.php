
</body>
</html>


<?php

// Database configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_pass = "your_password";

// Function to connect to the database
function connectDB($host, $name, $user, $pass) {
  $conn = new mysqli($host, $user, $pass, $name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to add an item to the cart
function add_to_cart($conn, $user_id, $product_id, $quantity) {
  // Check if the user already has items in the cart
  $query = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // Item already exists, increment quantity
    $query = "UPDATE cart SET quantity = quantity + $quantity WHERE user_id = '$user_id' AND product_id = '$product_id'";
    if ($conn->query($query) === TRUE) {
      echo "Item quantity updated successfully!";
    } else {
      echo "Error updating item quantity.";
    }
  } else {
    // Item doesn't exist, add it to the cart
    $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')";
    if ($conn->query($query) === TRUE) {
      echo "Item added to cart successfully!";
    } else {
      echo "Error adding item to cart.";
    }
  }
}

// Function to view the cart
function view_cart($conn) {
  $query = "SELECT c.product_id, p.name, p.price, c.quantity FROM cart c JOIN products p ON c.product_id = p.id";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    echo "<table border='1'><tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";
    while($row = $result->fetch_assoc()) {
      $total = $row['price'] * $row['quantity'];
      echo "<tr><td>" . $row['name'] . "</td><td>" . $row['price'] . "</td><td>" . $row['quantity'] . "</td><td>" . $total . "</td></tr>";
    }
    echo "</table>";
  } else {
    echo "Cart is empty.";
  }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($conn, $user_id, $product_id, $quantity) {
    // Check if the item exists in the cart
    $query = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Update the quantity
        $query = "UPDATE cart SET quantity = '$quantity' WHERE user_id = '$user_id' AND product_id = '$product_id'";
        if ($conn->query($query) === TRUE) {
            echo "Cart quantity updated successfully!";
        } else {
            echo "Error updating cart quantity.";
        }
    } else {
        echo "Item not found in cart.";
    }
}

// Function to delete an item from the cart
function delete_from_cart($conn, $user_id, $product_id) {
    $query = "DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
    if ($conn->query($query) === TRUE) {
        echo "Item removed from cart successfully!";
    } else {
        echo "Error deleting item from cart.";
    }
}


// *** Example usage (This part would typically be in a separate PHP file and called from a form) ***

// Connect to the database
$conn = connectDB($db_host, $db_name, $db_user, $db_pass);

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Add to cart
  if (isset($_POST["add_to_cart"])) {
    $user_id = "1"; // Replace with the actual user ID
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    add_to_cart($conn, $user_id, $product_id, $quantity);
  }

  // Update cart quantity
  if (isset($_POST["update_cart"])) {
    $user_id = "1";
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    update_cart_quantity($conn, $user_id, $product_id, $quantity);
  }

  // Delete from cart
  if (isset($_POST["delete_from_cart"])) {
    $user_id = "1";
    $product_id = $_POST["product_id"];
    delete_from_cart($conn, $user_id, $product_id);
  }

  // View the cart
  view_cart($conn);
} else {
  // If the form hasn't been submitted, display the form
  echo "<form method='post'>";
  echo "Product ID: <input type='number' name='product_id' value=''>";
  echo "<br>Quantity: <input type='number' name='quantity' value='1'>";
  echo "<br><input type='submit' name='add_to_cart' value='Add to Cart'>";
  echo "</form>";
}
?>
