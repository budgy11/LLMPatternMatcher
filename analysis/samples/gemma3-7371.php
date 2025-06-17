
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Our Products</h1>

  <h2>Product 1</h2>
  <form method="post">
    <input type="hidden" name="product_id" value="1">
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="1" min="1">
    <button type="submit" name="add_to_cart">Add to Cart</button>
  </form>

  <h2>Product 2</h2>
  <form method="post">
    <input type="hidden" name="product_id" value="2">
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="1" min="1">
    <button type="submit" name="add_to_cart">Add to Cart</button>
  </form>


</body>
</html>


<?php
session_start(); // Start the session to maintain user data

// Database connection details
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to add a product to the cart
function addToCart($conn, $product_id, $quantity) {
  // Check if the product already exists in the cart
  $query = "SELECT * FROM cart WHERE product_id = '$product_id'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // Product already in cart, update quantity
    $row = $result->fetch_assoc();
    $quantity_new = $row['quantity'] + $quantity;
    $query = "UPDATE cart SET quantity = '$quantity_new' WHERE product_id = '$product_id'";
    if ($conn->query($query) === TRUE) {
      // Optionally, you can also update the total price here
    } else {
      echo "Error updating cart: " . $conn->error;
    }
  } else {
    // Product not in cart, add it
    $query = "INSERT INTO cart (product_id, quantity) VALUES ('$product_id', '$quantity')";
    if ($conn->query($query) === TRUE) {
      // Optionally, you can also update the total price here
    } else {
      echo "Error adding to cart: " . $conn->error;
    }
  }
}

// Function to display the cart contents
function displayCart($conn) {
  $query = "SELECT * FROM cart";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    echo "<h2>Cart Contents</h2>";
    while ($row = $result->fetch_assoc()) {
      $product_id = $row['product_id'];
      $quantity = $row['quantity'];
      $product_name = mysqli_query($conn, "SELECT product_name FROM products WHERE product_id = '$product_id'").$row['product_name']; //get product name
      echo "<tr>";
      echo "<td>" . $product_name . "</td>";
      echo "<td>" . $quantity . "</td>";
      // Calculate total price for the item (replace with your actual price logic)
      $price = mysqli_query($conn, "SELECT price FROM products WHERE product_id = '$product_id'").$row['price'];  // get price
      echo "<td>$" . $price . "</td>";
      echo "</tr>";
    }
  } else {
    echo "<p>Your cart is empty.</p>";
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($conn, $product_id, $quantity) {
  // Check if the product exists in the cart
  $query = "SELECT * FROM cart WHERE product_id = '$product_id'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // Product already in cart, update quantity
    $row = $result->fetch_assoc();
    $quantity_new = $quantity; // Use the provided quantity
    $query = "UPDATE cart SET quantity = '$quantity_new' WHERE product_id = '$product_id'";
    if ($conn->query($query) === TRUE) {
      // Optionally, you can also update the total price here
    } else {
      echo "Error updating cart: " . $conn->error;
    }
  } else {
    echo "<p>Product not found in cart.</p>";
  }
}

// Function to remove an item from the cart
function removeItemFromCart($conn, $product_id) {
  $query = "DELETE FROM cart WHERE product_id = '$product_id'";
  if ($conn->query($query) === TRUE) {
    echo "<p>Product removed from cart.</p>";
  } else {
    echo "Error removing from cart: " . $conn->error;
  }
}

// --- Example Usage (Handle the purchase process) ---

// Check if the purchase form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check if the 'remove_product' parameter is present (for removing items)
  if (isset($_POST["remove_product"])) {
    $product_id_to_remove = $_POST["remove_product"];
    removeItemFromCart($conn, $product_id_to_remove);
  } else {
    // Process the purchase (e.g., update quantity, clear the cart)
    // This is a simplified example.  In a real application, you'd
    // integrate with a payment gateway, handle order confirmation, etc.

    //  Example: Update quantity of all items in the cart
    //   This would require looping through all products in the cart
    //   and updating their quantities.
  }
}

// --- Display the cart contents ---
displayCart($conn);

?>
