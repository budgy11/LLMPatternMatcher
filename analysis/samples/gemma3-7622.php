    <label for="name">Product Name:</label>
    <input type="text" id="name" name="name" required>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" step="0.01" required>

    <button type="submit">Add to Cart</button>
  </form>

  <a href="cart.php">View Cart</a> <!-- Link to your cart page -->


</body>
</html>


<?php

// --- Database Configuration (Replace with your actual database details) ---
$db_host = "localhost";
$db_name = "shop_db";
$db_user = "your_user";
$db_password = "your_password";
// --- End Database Configuration ---


// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and Validate Input
  $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $quantity = filter_input(INPUT_POST, "quantity", FILTER_VALIDATE_INT);
  $total_price = filter_input(INPUT_POST, "total_price", FILTER_VALIDATE_FLOAT);


  // Validate inputs -  Crucial for security
  if (empty($name) || empty($email) || empty($quantity) || empty($total_price)) {
    $error_message = "Please fill in all fields.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email format.";
  } elseif ($quantity <= 0) {
    $error_message = "Quantity must be greater than zero.";
  } elseif ($total_price <= 0) {
    $error_message = "Total price must be greater than zero.";
  } else {
    // Simulate a product price (replace with your actual product price logic)
    $product_price = 25.00; // Example price

    // Calculate the total price
    $order_total = $quantity * $product_price;

    // ---  Data Handling ---
    $name = htmlspecialchars($name);  //Escape HTML characters for security
    $email = filter_var($email, FILTER_SANITIZE_EMAIL); //Sanitize again for extra safety

    // ---  Database Interaction ---
    try {
      // Connect to the database
      $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Prepare the SQL statement
      $stmt = $pdo->prepare("INSERT INTO orders (name, email, quantity, total_price) VALUES (:name, :email, :quantity, :total_price)");

      // Bind parameters
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':quantity', $quantity);
      $stmt->bindParam(':total_price', $total_price);

      // Execute the statement
      $stmt->execute();

      // Display success message
      echo "<p style='color:green;'>Order placed successfully! Thank you, " . htmlspecialchars($name) . ".</p>";

    } catch (PDOException $e) {
      // Handle database errors
      echo "<p style='color:red;'>Database error: " . $e->getMessage() . "</p>";
    }
  }
}
?>
