    <h2>Select Products</h2>

    <h3>T-Shirt (ID: 1) - Price: $20</h3>
    <label for="product1_quantity">Quantity:</label>
    <input type="number" id="product1_quantity" name="product1_quantity" value="0" min="0">

    <h3>Jeans (ID: 2) - Price: $50</h3>
    <label for="product2_quantity">Quantity:</label>
    <input type="number" id="product2_quantity" name="product2_quantity" value="0" min="0">

    <h3>Sneakers (ID: 3) - Price: $80</h3>
    <label for="product3_quantity">Quantity:</label>
    <input type="number" id="product3_quantity" name="product3_quantity" value="0" min="0">

    <input type="submit" value="Place Order">
</form>

</body>
</html>


<?php

// Database connection (Replace with your actual database credentials)
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

// Function to handle the purchase process
function processPurchase($cart, $customerName, $customerEmail, $payment_method) {
  // 1. Validate Cart
  if (empty($cart)) {
    return "Error: Cart is empty. Please add items before purchasing.";
  }

  // 2. Calculate Total Amount
  $totalAmount = 0;
  foreach ($cart as $item => $quantity) {
    // Assume you have a product table with price
    // Replace 'products' and 'price' with your actual table and column names
    $product = getProduct($item); // Function to retrieve product details from your database
    if ($product) {
      $totalAmount += $product['price'] * $quantity;
    } else {
      return "Error: Product '$item' not found.";
    }
  }

  // 3. Database Interaction (Create Order)
  $order_id = generateOrderID(); // Function to generate a unique order ID
  $sql = "INSERT INTO orders (customer_name, customer_email, order_date, total_amount, order_id)
          VALUES ('$customerName', '$customerEmail', NOW(), $totalAmount, '$order_id')";

  if ($conn->query($sql) === TRUE) {
    $order_id = $conn->insert_id; // Get the newly inserted order ID
    echo "Order placed successfully! Order ID: $order_id";

    // 4. Update Cart (Mark items as purchased)
    foreach ($cart as $item => $quantity) {
      $update_sql = "UPDATE products SET quantity_in_stock = quantity_in_stock - $quantity WHERE product_id = '$item'";
      if ($conn->query($update_sql) === TRUE) {
        // Optionally, you can log this update
      } else {
        echo "Error updating product quantity for item '$item'.";
      }
    }
  } else {
    echo "Error: " . $conn->error;
  }
}


// --- Helper Functions ---

// Function to get product details from the database
function getProduct($product_id) {
  // Replace 'products' and 'product_id' with your actual table and column names
  $sql = "SELECT * FROM products WHERE product_id = '$product_id'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    return $product;
  } else {
    return null;
  }
}

// Function to generate a unique order ID
function generateOrderID() {
  //  You can use UUID, a sequential number, or any other unique ID generation method
  return uniqid(); // Using uniqid for simplicity
}

// --- Example Usage (Form Handling) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get cart data from the form
  $cart = $_POST['cart'];
  $customerName = $_POST['customer_name'];
  $customerEmail = $_POST['customer_email'];
  $payment_method = $_POST['payment_method']; // You can add payment gateway integration here

  // Process the purchase
  processPurchase($cart, $customerName, $customerEmail, $payment_method);
}


// --- HTML Form (For User Input) ---
?>
