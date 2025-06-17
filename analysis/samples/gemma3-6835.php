    </select>
    <br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="1">
    <br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., using mysqli or PDO)
// For this example, let's assume we're using mysqli

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

// Function to handle the purchase process
function processPurchase($order_data) {
  global $conn;

  // Validate the order data (basic validation, add more robust validation as needed)
  if (empty($order_data['product_id']) || empty($order_data['quantity']) || empty($order_data['customer_name']) || empty($order_data['email'])) {
    return "Error: Please fill in all required fields.";
  }

  // Sanitize and prepare the data for the database
  $product_id = $conn->real_escape_string($order_data['product_id']);
  $quantity = $conn->real_escape_string($order_data['quantity']);
  $customer_name = $conn->real_escape_string($order_data['customer_name']);
  $email = $conn->real_escape_string($order_data['email']);

  //  Fetch product details from the database
  $sql = "SELECT product_id, product_name, price FROM products WHERE product_id = '$product_id'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $product_name = $row['product_name'];
    $price = $row['price'];
  } else {
    return "Error: Product not found.";
  }


  // Calculate the total price
  $total_price = $price * $quantity;

  // Prepare the SQL statement for inserting the order data
  $sql = "INSERT INTO orders (product_id, product_name, quantity, customer_name, email, total_price, order_date)
          VALUES ('$product_id', '$product_name', '$quantity', '$customer_name', '$email', '$total_price', NOW())";

  if ($conn->query($sql) === TRUE) {
    return "Order placed successfully! Order ID: " . $conn->insert_id;
  } else {
    return "Error: " . $conn->error;
  }
}


// -----  Example Usage (simulating a form submission) -----

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the order data from the form
  $order_data = array(
    "product_id" => $_POST["product_id"],
    "quantity" => $_POST["quantity"],
    "customer_name" => $_POST["customer_name"],
    "email" => $_POST["email"]
  );

  // Process the purchase
  $result = processPurchase($order_data);

  // Display the result
  echo "<p>" . $result . "</p>";
}

?>
