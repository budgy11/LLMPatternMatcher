<?php endif; ?>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$dbHost = "localhost";
$dbName = "shop_db";
$dbUser = "your_username";
$dbPassword = "your_password";

// Function to connect to the database
function connectToDatabase() {
  $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to add a product to the cart
function addToCart($conn, $product_id, $quantity) {
  // Check if the product already exists in the cart
  $query = "SELECT * FROM cart WHERE product_id = '$product_id'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // Product already in cart, update quantity
    $row = $result->fetch_assoc();
    $query = "UPDATE cart SET quantity = quantity + $quantity WHERE product_id = '$product_id'";
    if ($conn->query($query) === TRUE) {
      echo "Product quantity updated successfully!";
    } else {
      echo "Error updating product quantity: " . $conn->error;
    }
  } else {
    // Product not in cart, add it
    $query = "INSERT INTO cart (product_id, quantity) VALUES ('$product_id', '$quantity')";
    if ($conn->query($query) === TRUE) {
      echo "Product added to cart successfully!";
    } else {
      echo "Error adding product to cart: " . $conn->error;
    }
  }
}

// Function to view the cart
function viewCart($conn) {
  $query = "SELECT * FROM cart";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    echo "<h2>Your Shopping Cart</h2>";
    while ($row = $result->fetch_assoc()) {
      $product_id = $row["product_id"];
      $quantity = $row["quantity"];
      $product_name = getProductName($conn, $product_id); // Call a helper function to get product name

      echo "<tr>";
      echo "<td>" . $product_name . "</td>";
      echo "<td>" . $quantity . "</td>";
      echo "<td><button onclick='removeFromCart(" . $product_id . ")'>Remove</button></td>"; // Button to remove
      echo "</tr>";
    }
  } else {
    echo "<p>Your cart is empty.</p>";
  }
}

// Helper function to get the product name based on product_id
function getProductName($conn, $product_id) {
    $query = "SELECT * FROM products WHERE product_id = '$product_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row["product_name"];
    } else {
        return "Unknown Product";
    }
}


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check if 'add_to_cart' is set
  if (isset($_POST["add_to_cart"])) {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    addToCart($conn, $product_id, $quantity);
  }
}

// Database connection
$conn = connectToDatabase();

// View the cart (call this function to display the cart content)
viewCart($conn);

//  Example HTML for the form and display
?>
