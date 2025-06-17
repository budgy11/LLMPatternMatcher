    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <label for="payment_method">Payment Method:</label>
    <select id="payment_method" name="payment_method">
        <option value="credit_card">Credit Card</option>
        <option value="paypal">PayPal</option>
    </select><br><br>

    <input type="submit" value="Place Order">
</form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$dbHost = "localhost";
$dbUser = "your_username";
$dbPass = "your_password";
$dbName = "your_database_name";

// Function to connect to the database
function connectToDatabase() {
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to add a product to the cart
function addToCart($conn, $product_id, $quantity) {
  // Assuming you have a 'cart' table with columns:
  // - user_id (foreign key to a 'users' table - not implemented here)
  // - product_id
  // - quantity

  //  Example:
  $sql = "INSERT INTO cart (product_id, quantity) VALUES ('$product_id', '$quantity')";

  if ($conn->query($sql) === TRUE) {
    return true;
  } else {
    return false;
  }
}

// Function to retrieve the cart items
function getCartItems($conn) {
    $sql = "SELECT product_id, quantity FROM cart";
    $result = $conn->query($sql);

    $cart_items = array();
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $product_id = $row["product_id"];
        $quantity = $row["quantity"];

        // You'll need to fetch product details from a 'products' table
        // based on the product_id.  This is a placeholder.
        $product = getProductDetails($conn, $product_id);

        if ($product) {
          $cart_items[] = array(
            'product_id' => $product_id,
            'product_name' => $product['product_name'],
            'quantity' => $quantity,
            'price' => $product['price'] // Assuming you have a 'products' table with a price
          );
        }
      }
    }
    return $cart_items;
}


// Placeholder function to get product details (replace with your actual database query)
function getProductDetails($conn, $product_id) {
  // This is a placeholder.  You'll need to adapt this to your database schema.
  // You would typically query the 'products' table based on the $product_id.
  // Example:
  // $sql = "SELECT product_name, price FROM products WHERE product_id = '$product_id'";
  // $result = $conn->query($sql);

  // For this example, let's just return some dummy data
  if ($product_id == 1) {
    return array('product_name' => 'Laptop', 'price' => 1200);
  } elseif ($product_id == 2) {
    return array('product_name' => 'Mouse', 'price' => 25);
  } else {
    return null; // Product not found
  }
}


// --- Example Usage (Handle Form Submission) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Check if 'add_to_cart' button was clicked
  if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Connect to the database
    $conn = connectToDatabase();

    // Add to cart
    if (addToCart($conn, $product_id, $quantity)) {
      echo "Product added to cart!  Check your cart.";
    } else {
      echo "Failed to add product to cart.";
    }

    // Close the connection
    $conn->close();
  }
}

// --- Display the Cart (for demonstration) ---

$cart_items = getCartItems($conn);

?>
