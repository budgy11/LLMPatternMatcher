
  <hr>

  <!-- Remove Item Form -->
  <h2>Remove Item</h2>
  <form method="post">
    <label for="item_id_remove">Item ID to Remove:</label>
    <input type="number" id="item_id_remove" name="item_id" required><br><br>
    <button type="submit" name="remove_item">Remove Item</button>
  </form>

  <hr>
  <!-- Update Quantity Form -->
    <h2>Update Quantity</h2>
    <form method="post">
        <label for="item_id_update">Item ID to Update:</label>
        <input type="number" id="item_id_update" name="item_id" required><br><br>

        <label for="new_quantity">New Quantity:</label>
        <input type="number" id="new_quantity" name="quantity" min="1" required><br><br>
        <button type="submit" name="update_quantity">Update Quantity</button>
    </form>

</body>
</html>


<?php

// Configuration (Database connection details)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (basic example - expand as needed)
  $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $quantity = filter_input(INPUT_POST, "quantity", FILTER_VALIDATE_INT);
  $product = filter_input(INPUT_POST, "product", FILTER_SANITIZE_STRING);

  // Check if the required fields are filled
  if (empty($name) || empty($email) || empty($quantity) || empty($product)) {
    $error = "Please fill in all the required fields.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Please enter a valid email address.";
  } elseif ($quantity <= 0) {
    $error = "Quantity must be a positive number.";
  } else {
    //  Database interaction (Example - adjust to your database structure)
    try {
      // Connect to the database
      $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // For error handling

      // Prepare the SQL statement
      $stmt = $pdo->prepare("INSERT INTO orders (name, email, quantity, product) VALUES (:name, :email, :quantity, :product)");

      // Bind the parameters
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':quantity', $quantity);
      $stmt->bindParam(':product', $product);

      // Execute the statement
      $stmt->execute();

      // Display a success message
      echo "<p style='color:green;'>Order placed successfully! Thank you, " . htmlspecialchars($name) . ".</p>";
    } catch (PDOException $e) {
      // Handle database errors
      echo "<p style='color:red;'>Error placing order: " . $e->getMessage() . "</p>";
    }
  }
}
?>
