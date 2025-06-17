

<?php

// Database connection (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to handle adding a product to the cart
function addToCart($conn, $product_id, $quantity) {
  // Check if the product already exists in the cart
  $query = "SELECT * FROM cart WHERE product_id = '$product_id'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // Product exists, update the quantity
    $row = $result->fetch_assoc();
    $query = "UPDATE cart SET quantity = quantity + $quantity WHERE product_id = '$product_id'";
    if ($conn->query($query) === TRUE) {
      return "Product added to cart.";
    } else {
      return "Error updating cart: " . $conn->error;
    }
  } else {
    // Product doesn't exist, add it to the cart
    $query = "INSERT INTO cart (product_id, quantity) VALUES ('$product_id', $quantity)";
    if ($conn->query($query) === TRUE) {
      return "Product added to cart.";
    } else {
      return "Error adding product to cart: " . $conn->error;
    }
  }
}

// Function to display the cart contents
function displayCart($conn) {
  $query = "SELECT * FROM cart";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    echo "<h2>Your Shopping Cart</h2>";
    while ($row = $result->fetch_assoc()) {
      $product_id = $row['product_id'];
      $product_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id = '$product_id'"));
      $quantity = $row['quantity'];
      $price = $product_name['price'];

      echo "<tr>";
      echo "<td><img src='$product_name[image]' alt='$product_name[name]' width='100'></td>";
      echo "<td>$product_name[name]</td>";
      echo "<td>$price</td>";
      echo "<td><input type='number' name='quantity[$product_id]' value='$quantity' min='1' max='100'></td>"; // Adjust max limit
      echo "<td><button onclick='removeFromCart('$product_id')'>Remove</button></td>";
      echo "</tr>";
    }
  } else {
    echo "<h2>Your Shopping Cart is Empty</h2>";
  }
}

// Function to remove a product from the cart
function removeFromCart($conn, $product_id) {
  $query = "DELETE FROM cart WHERE product_id = '$product_id'";
  if ($conn->query($query) === TRUE) {
    return "Product removed from cart.";
  } else {
    return "Error removing product from cart: " . $conn->error;
  }
}

// --- Example Usage (Simulating a purchase) ---

// 1. Handle adding to cart (e.g., from a form submission)
// $product_id = $_POST['product_id'];
// $quantity = $_POST['quantity'];
// $message = addToCart($conn, $product_id, $quantity);
// echo $message;

// 2. Display the cart contents
displayCart($conn);

// 3.  Simulate a "Checkout" process (This is a simplified example)
//  You would typically integrate this with a payment gateway.
// echo "<br><br><h2>Thank You for Your Order!</h2>";
// echo "<p>Your order has been placed.  (This is a placeholder.)</p>";

// Close the database connection
$conn->close();

?>
