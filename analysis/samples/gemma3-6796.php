
</body>
</html>


<?php

// Database connection details (Replace with your actual values)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (Important for security)
  $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $quantity = filter_input(INPUT_POST, "quantity", FILTER_VALIDATE_INT);
  $product_name = filter_input(INPUT_POST, "product_name", FILTER_SANITIZE_STRING); //Example for getting product name

  // Basic validation
  if (empty($name) || empty($email) || empty($quantity) || empty($product_name)) {
    $error_message = "Please fill in all fields.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Please enter a valid email address.";
  } elseif ($quantity <= 0) {
    $error_message = "Quantity must be a positive number.";
  } else {
    // Process the purchase
    // 1.  Insert into a Purchases table (Example)
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO purchases (name, email, quantity, product_name) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt->prepare($sql)) {
      die("Prepare failed: " . $stmt->error);
    }


    $stmt->bind_param("ssii", $name, $email, $quantity, $product_name);

    if (!$stmt->execute()) {
      die("Execute failed: " . $stmt->error);
    }

    $purchase_id = $conn->insert_id;

    $stmt->close();
    $conn->close();

    // Display success message
    echo "<div class='success'>Purchase successful! Purchase ID: " . $purchase_id . "</div>";

  }
}
?>
